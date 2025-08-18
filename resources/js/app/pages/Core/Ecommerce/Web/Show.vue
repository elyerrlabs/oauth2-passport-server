<template>
    <v-ecommerce>
        <div class="q-pa-sm q-pa-md-lg">
            <!-- Breadcrumbs - Stack on mobile -->
            <q-breadcrumbs class="q-mb-sm q-mb-md-md" active-color="primary">
                <q-breadcrumbs-el label="Home" icon="home" to="/" />
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
                                :src="optimizeImage(image.url)"
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
                                :src="optimizeImage(image.url)"
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
                            ({{ product?.reviews_count || 0 }} reviews)
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
                                    ? `In Stock (${product.stock})`
                                    : "Out of Stock"
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
                            Save {{ product?.discount }}%
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
                                {{
                                    selectedAttributes[attribute.slug] ||
                                    "Select"
                                }}
                            </span>
                        </div>
                        <div class="row q-gutter-xs">
                            <q-btn
                                v-for="(value, valueIndex) in attribute.value"
                                :key="`attribute-value-${valueIndex}`"
                                :label="value"
                                outline
                                :color="
                                    selectedAttributes[attribute.slug] === value
                                        ? 'primary'
                                        : 'grey-7'
                                "
                                size="sm"
                                @click="
                                    selectedAttributes[attribute.slug] = value
                                "
                                class="text-capitalize"
                            />
                        </div>
                    </div>

                    <!-- Quantity - Stack on mobile -->
                    <div class="row items-center q-mb-lg">
                        <div
                            class="col-12 col-sm-auto text-subtitle2 q-mr-sm-md q-mb-xs-sm"
                        >
                            Quantity:
                        </div>
                        <div class="col-auto">
                            <q-input
                                v-model="quantity"
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
                            Max {{ product?.stock || 10 }} per customer
                        </div>
                    </div>

                    <!-- Action Buttons - Stack on mobile -->
                    <div class="row q-gutter-sm q-mb-lg">
                        <q-btn
                            color="primary"
                            icon="shopping_cart"
                            label="Add to Cart"
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
                    </div>

                    <!-- Delivery Info - Hidden on mobile -->
                    <div class="q-mb-lg" v-if="$q.screen.gt.xs">
                        <!--
                            <div class="row items-center q-gutter-sm">
                                <q-icon
                                name="local_shipping"
                                size="sm"
                                color="grey-7"
                                />
                                <div class="text-caption text-grey-8">
                                    Free delivery on orders over $50
                                </div>
                            </div>
                            -->
                        <div class="row items-center q-gutter-sm">
                            <q-icon
                                name="assignment_return"
                                size="sm"
                                color="grey-7"
                            />
                            <div class="text-caption text-grey-8">
                                30-day free returns
                            </div>
                        </div>
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
                    label="Description"
                />
                <q-tab name="specs" icon="list_alt" label="Specification" />
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
            <v-products title="Related Products" :products="related_products" />
        </div>

        <!-- Review Dialog -->
        <q-dialog v-model="showReviewDialog">
            <q-card style="width: 100%; max-width: 500px">
                <q-card-section>
                    <div class="text-h6">Write a Review</div>
                </q-card-section>

                <q-card-section class="q-pt-none">
                    <div class="q-mb-md">
                        <div class="text-subtitle2 q-mb-sm">Rating</div>
                        <q-rating
                            v-model="newReview.rating"
                            size="2em"
                            color="orange"
                        />
                    </div>

                    <q-input
                        v-model="newReview.title"
                        label="Review Title"
                        class="q-mb-md"
                        outlined
                    />

                    <q-input
                        v-model="newReview.comment"
                        label="Your Review"
                        type="textarea"
                        outlined
                        rows="5"
                    />
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn flat label="Cancel" color="primary" v-close-popup />
                    <q-btn
                        label="Submit"
                        color="primary"
                        @click="submitReview"
                    />
                </q-card-actions>
            </q-card>
        </q-dialog>

        <!-- Question Dialog -->
        <q-dialog v-model="showQuestionDialog">
            <q-card style="width: 100%; max-width: 500px">
                <q-card-section>
                    <div class="text-h6">Ask a Question</div>
                </q-card-section>

                <q-card-section class="q-pt-none">
                    <q-input
                        v-model="newQuestion.text"
                        label="Your Question"
                        type="textarea"
                        outlined
                        rows="5"
                    />
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn flat label="Cancel" color="primary" v-close-popup />
                    <q-btn
                        label="Submit"
                        color="primary"
                        @click="submitQuestion"
                    />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </v-ecommerce>
</template>

<script>
export default {
    data() {
        return {
            product: null,
            related_products: [],
            q: null,

            // UI State
            productImageSlide: 0,
            productDetailTab: "description",
            selectedAttributes: {},
            quantity: 1,
            qaSearch: "",
            showReviewDialog: false,
            showQuestionDialog: false,
            isInWishlist: false,
            rating: 4.5, // Default rating, should come from API
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
        this.fetchProductData();
    },

    methods: {
        goTo(tag) {
            console.log(tag);

            window.location.href = `${this.product?.links?.search}?q=${tag}`;
        },

        async fetchProductData() {
            try {
                // Fetch main product data
                const res = await this.$server.get(
                    this.$page.props.routes["show"]
                );

                if (res.status === 200) {
                    this.product = res.data.data;

                    this.fetchRelatedProducts(this.product);
                }
            } catch (error) {
                console.error("Error fetching product data:", error);
                this.$q.notify({
                    message: "Failed to load product details",
                    color: "negative",
                });
            }
        },

        async fetchRelatedProducts(item) {
            try {
                const response = await this.$server.get(item.links.search, {
                    params: {
                        random: true,
                        per_page: 30,
                        tags: item.tags.map((tag) => tag.name).join(","),
                    },
                });

                if (response.status === 200) {
                    this.related_products = response.data.data;
                }
            } catch (error) {
                console.error("Error fetching related products:", error);
            }
        },

        async checkWishlistStatus() {
            try {
                // In a real app, this would check against your API
                // This is just a mock implementation
                this.isInWishlist = false;
            } catch (error) {
                console.error("Error checking wishlist status:", error);
            }
        },

        optimizeImage(url) {
            if (!url) return "";

            if (url.includes("cloudinary.com")) {
                return url.replace(
                    "/upload/",
                    "/upload/w_500,h_250,c_fill,q_auto,f_auto/"
                );
            }

            if (url.includes("imgix.net")) {
                return `${url}?w=500&h=250&fit=crop&auto=format,compress`;
            }

            return url;
        },

        addToCart() {
            const item = {
                product: this.product,
                quantity: this.quantity,
                selectedAttributes: this.selectedAttributes,
            };

            // In a real app, this would call your API
            console.log("Adding to cart:", item);

            this.$q.notify({
                message: `${this.product.name} added to cart`,
                color: "positive",
                icon: "shopping_cart",
                position: "top-right",
            });
        },

        buyNow() {
            this.addToCart();
            // In a real app, this would navigate to checkout
            this.$router.push("/checkout");
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
