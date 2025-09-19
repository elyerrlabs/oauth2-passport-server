<template>
    <div class="py-2 max-h-60 overflow-y-auto">
        <!-- Loader -->
        <div v-if="loading" class="flex justify-center items-center py-6">
            <svg
                class="animate-spin h-6 w-6 text-primary-600"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
            >
                <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                ></circle>
                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                ></path>
            </svg>
        </div>

        <!-- Categories -->
        <a
            v-else
            v-for="category in categories"
            :key="category.id"
            class="flex items-center px-4 py-3 hover:bg-gray-100 transition-colors cursor-pointer"
            @click="goTo(category.links.index)"
        >
            <i
                :class="['mdi', category.icon.icon, 'mr-3 text-primary-600']"
            ></i>
            <span>{{ category.name }}</span>
        </a>
    </div>
</template>

<script>
export default {
    data() {
        return {
            categories: [],
            loading: true,
        };
    },

    created() {
        this.getCategories();
    },

    methods: {
        goTo(url) {
            window.location.href = url;
        },

        async getCategories() {
            this.loading = true;
            try {
                const res = await this.$server.get(
                    this.$page.props.api.ecommerce.categories
                );
                if (res.status === 200) {
                    this.categories = res.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$notify.error(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
