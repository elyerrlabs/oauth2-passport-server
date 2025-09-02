<template>
    <div class="q-pa-sm q-pa-md">
        <div v-if="title" class="q-mb-md text-h6">{{ title }}</div>

        <div class="row q-col-gutter-sm">
            <div
                v-for="product in products"
                :key="product.id"
                class="col-xs-6 col-sm-4 col-md-3 col-lg-2"
            >
                <q-card flat bordered class="compact-product-card">
                    <!-- Image Carousel -->
                    <q-carousel
                        v-if="product.images && product.images.length"
                        v-model="carouselModels[product.id]"
                        height="120px"
                        swipeable
                        animated
                        navigation
                        autoplay
                        infinite
                        control-color="primary"
                        navigation-position="bottom"
                        class="product-carousel"
                    >
                        <q-carousel-slide
                            v-for="(img, index) in product.images"
                            :key="index"
                            :name="index"
                            :img-src="img.url"
                        />
                    </q-carousel>

                    <q-card-section class="q-pa-sm">
                        <div class="text-caption text-grey-8 ellipsis-2-lines">
                            {{ product.name }}
                        </div>
                        <div class="text-subtitle2 text-weight-bold">
                            {{ product.symbol }} {{ product.format_price }}
                        </div>
                    </q-card-section>

                    <q-card-actions class="q-px-sm q-pb-sm">
                        <q-btn
                            dense
                            color="primary"
                            size="md"
                            :label="__('Buy')"
                            @click="goTo(product?.links?.show)"
                        />
                        <q-space />
                        <q-btn
                            dense
                            flat
                            round
                            icon="favorite_border"
                            size="sm"
                        />
                    </q-card-actions>
                </q-card>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        products: {
            required: true,
            type: Array,
        },
        title: {
            type: String,
            default: "",
        },
    },
    data() {
        return {
            carouselModels: {},
        };
    },
    watch: {
        products: {
            immediate: true,
            handler(products) {
                this.carouselModels = {};
                for (const product of products) {
                    this.carouselModels[product.id] = 0;
                }
            },
        },
    },

    methods: {
        goTo(link) {
            window.location.href = link;
        },
    },
};
</script>

<style lang="scss">
.compact-product-card {
    max-width: 100%;

    .product-carousel {
        max-height: 120px;
        img {
            object-fit: cover;
        }
    }

    .q-card__section {
        padding: 8px;
    }

    .q-btn {
        font-size: 0.75rem;
        padding: 0 8px;
    }
}
</style>
