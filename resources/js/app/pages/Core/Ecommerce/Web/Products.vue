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
        this.getProducts();
    },

    methods: {
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
                    this.products = res.data.data.map((product) => ({
                        ...product,
                        currentSlide: 0,
                    }));
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
    },
};
</script>
