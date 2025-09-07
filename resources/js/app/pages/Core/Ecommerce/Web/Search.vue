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
            app_name: "",
        };
    },

    created() {
        this.app_name = this.$page.props.app_name;
        this.getProducts();
    },

    methods: {
        async getProducts() {
            try {
                const currentParams = Object.fromEntries(
                    new URLSearchParams(window.location.search)
                );

                const params = {
                    ...currentParams,
                    per_page: 150,
                    random: true,
                };

                const res = await this.$server.get(
                    this.$page.props.routes.search_api,
                    { params }
                );

                if (res.status === 200) {
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
