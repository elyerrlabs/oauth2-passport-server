<template>
    <v-ecommerce>
        <div class="q-pa-sm">
            <v-products :products="products" />
        </div>
    </v-ecommerce>
</template>

<script>
export default {
    data() {
        return {
            products: [],
        };
    },

    created() {
        this.getProducts(window.location.href);
    },

    methods: {
        async getProducts(url) {
            try {
                const res = await this.$server.get(url, {
                    params: {
                        per_page: 150,
                        random: true,
                    },
                });

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
    },
};
</script>
