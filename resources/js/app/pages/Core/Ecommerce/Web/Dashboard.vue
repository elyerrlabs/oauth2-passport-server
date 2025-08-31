<template>
    <v-ecommerce>
        <q-carousel
            animated
            v-model="slide"
            arrows
            navigation
            infinite
            autoplay
            :interval="5000"
            height="300px"
            class="rounded-borders full-width"
        >
            <q-carousel-slide
                v-for="(banner, index) in featured_categories"
                :key="`banner-${index}`"
                :name="index"
                class="relative-position"
                transition-hide="slide-down"
                transition-show="slide-up"
            >
                <q-img
                    :src="optimizeImage(banner?.images[0]?.url)"
                    loading="lazy"
                    style="height: 100%; width: 100%"
                >
                    <div class="absolute-bottom custom-caption">
                        <div class="text-h2">{{ banner.name }}</div>
                        <div class="text-subtitle1">
                            <small v-html="banner.description"></small>
                        </div>
                        <q-btn
                            color="primary"
                            label="Buy Now"
                            class="q-mt-sm"
                            @click.stop="goTo(banner?.links?.index)"
                        />
                    </div>
                </q-img>
            </q-carousel-slide>
        </q-carousel>
        <div class="q-pa-sm">
            <!-- Show Categories -->
            <div class="row q-col-gutter-md q-mb-lg">
                <div class="col-12">
                    <div class="text-h5 text-weight-bold q-mb-md">
                        Shop by Category
                    </div>
                </div>
                <div
                    class="col-xs-6 col-sm-4 col-md-3"
                    v-for="(category, index) in categories"
                    :key="`category-${category.id}`"
                >
                    <q-card
                        class="cursor-pointer"
                        @click="goTo(category?.links?.index)"
                    >
                        <q-carousel
                            v-model="category.currentSlide"
                            animated
                            infinite
                            arrows
                            height="250px"
                            transition-prev="slide-right"
                            transition-next="slide-left"
                            @mouseenter="pauseCarousel(category)"
                            @mouseleave="resumeCarousel(category)"
                        >
                            <q-carousel-slide
                                v-for="(img, imgIndex) in category.images"
                                :key="`category-img-${img.id}`"
                                :name="imgIndex"
                            >
                                <q-img
                                    :src="optimizeImage(img.url)"
                                    loading="lazy"
                                    :ratio="1"
                                    class="rounded-borders"
                                >
                                    <template v-slot:loading>
                                        <div
                                            class="absolute-full flex flex-center bg-grey-2"
                                        >
                                            <q-spinner
                                                color="primary"
                                                size="3em"
                                            />
                                        </div>
                                    </template>
                                </q-img>
                            </q-carousel-slide>
                        </q-carousel>

                        <div class="text-subtitle1 text-center q-pa-sm">
                            {{ category.name }}
                        </div>
                    </q-card>
                </div>
            </div>

            <!-- Featured Products -->
            <v-products
                :products="featured_products"
                title="Featured products"
            />

            <q-separator />

            <v-products :products="products" title="Another products" />
        </div>
    </v-ecommerce>
</template>

<script>
export default {
    data() {
        return {
            slide: 0,
            email: "",
            categories: [],
            featured_categories: [],
            products: [],
            featured_products: [],
            carouselIntervals: {}, // Store intervals for each category carousel
            app_name: "",
        };
    },

    created() {
        this.getFeaturedCategories();
        this.getCategories();
        this.app_name = this.$page.props.app_name;
        this.getProducts();
        this.getFeaturedProducts();
    },

    beforeUnmount() {
        // Clean up all intervals when component is destroyed
        Object.values(this.carouselIntervals).forEach(clearInterval);
    },

    methods: {
        goTo(link) {
            if (link) {
                window.location.href = link;
            }
        },

        optimizeImage(url) {
            if (!url) return "";

            // Example optimizations (adjust based on your image provider)
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

        startCarousel(category) {
            if (!category.images || category.images.length <= 1) return;

            this.carouselIntervals[category.id] = setInterval(() => {
                const current = category.currentSlide || 0;
                const next = (current + 1) % category.images.length;
                category.currentSlide = next;
            }, 3000);
        },

        pauseCarousel(category) {
            if (this.carouselIntervals[category.id]) {
                clearInterval(this.carouselIntervals[category.id]);
            }
        },

        resumeCarousel(category) {
            this.startCarousel(category);
        },

        subscribeNewsletter() {
            // Implement newsletter subscription logic
            console.log("Subscribing email:", this.email);
            // Reset email field
            this.email = "";
        },

        async getProducts() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes["search"],
                    {
                        params: {
                            per_page: 150,
                            random: true,
                        },
                    }
                );

                if (res.status == 200) {
                    this.products = res.data.data.map((product) => ({
                        ...product,
                        currentSlide: 0,
                    }));
                }
            } catch (error) {
                console.error("Error fetching products:", error);
            }
        },

        async getFeaturedProducts() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes["search"],
                    {
                        params: {
                            per_page: 50,
                            random: true,
                            featured: true,
                        },
                    }
                );

                if (res.status == 200) {
                    this.featured_products = res.data.data.map((product) => ({
                        ...product,
                        currentSlide: 0,
                    }));
                }
            } catch (error) {
                console.error("Error fetching products:", error);
            }
        },

        async getFeaturedCategories() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes["categories"],
                    {
                        params: {
                            featured: true,
                            random: true,
                            per_page: 25,
                        },
                    }
                );

                if (res.status == 200) {
                    this.featured_categories = res.data.data.map((cat) => ({
                        ...cat,
                        currentSlide: 0,
                    }));
                }
            } catch (error) {
                console.error("Error fetching featured categories:", error);
            }
        },

        async getCategories() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes["categories"],
                    {
                        params: {
                            random: true,
                            per_page: 100,
                        },
                    }
                );

                if (res.status == 200) {
                    this.categories = res.data.data.map((category) => ({
                        ...category,
                        currentSlide: 0,
                    }));

                    // Start carousels after data is loaded
                    this.$nextTick(() => {
                        this.categories.forEach((cat) =>
                            this.startCarousel(cat)
                        );
                    });
                }
            } catch (error) {
                console.error("Error fetching categories:", error);
            }
        },
    },
};
</script>

<style scoped>
.custom-caption {
    background: rgba(0, 0, 0, 0.5);
    padding: 20px;
    color: white;
    border-radius: 0 0 5px 5px;
    z-index: 1;
}

.ellipsis-2-lines {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

.q-card {
    transition: transform 0.3s;
    overflow: hidden;
}

.q-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

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
</style>
