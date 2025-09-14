<template>
    <aside class="w-full md:w-64 bg-white rounded-lg shadow p-6 h-fit">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Filters</h2>

        <!-- Price Range -->
        <div class="mb-6">
            <h3 class="font-medium text-gray-700 mb-2">Price Range</h3>
            <div class="space-y-2">
                <div class="flex items-center">
                    <input
                        type="radio"
                        id="price-all"
                        value="all"
                        v-model="priceFilter"
                        class="mr-2"
                    />
                    <label for="price-all" class="text-gray-600"
                        >All Prices</label
                    >
                </div>
                <div class="flex items-center">
                    <input
                        type="radio"
                        id="price-low"
                        value="low"
                        v-model="priceFilter"
                        class="mr-2"
                    />
                    <label for="price-low" class="text-gray-600"
                        >Under $50</label
                    >
                </div>
                <div class="flex items-center">
                    <input
                        type="radio"
                        id="price-medium"
                        value="medium"
                        v-model="priceFilter"
                        class="mr-2"
                    />
                    <label for="price-medium" class="text-gray-600"
                        >$50 - $100</label
                    >
                </div>
                <div class="flex items-center">
                    <input
                        type="radio"
                        id="price-high"
                        value="high"
                        v-model="priceFilter"
                        class="mr-2"
                    />
                    <label for="price-high" class="text-gray-600"
                        >Over $100</label
                    >
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="mb-6">
            <h3 class="font-medium text-gray-700 mb-2">Categories</h3>
            <div class="space-y-2">
                <div
                    v-for="category in categories"
                    :key="category.id"
                    class="flex items-center"
                >
                    <input
                        type="checkbox"
                        :id="'category-' + category.id"
                        :value="category.id"
                        v-model="selectedCategories"
                        class="mr-2"
                    />
                    <label
                        :for="'category-' + category.id"
                        class="text-gray-600"
                        >{{ category.name }}</label
                    >
                </div>
            </div>
        </div>

        <!-- Rating -->
        <div class="mb-6">
            <h3 class="font-medium text-gray-700 mb-2">Rating</h3>
            <div class="space-y-2">
                <div v-for="n in 5" :key="n" class="flex items-center">
                    <input
                        type="radio"
                        :id="'rating-' + n"
                        :value="n"
                        v-model="ratingFilter"
                        class="mr-2"
                    />
                    <label
                        :for="'rating-' + n"
                        class="text-gray-600 flex items-center"
                    >
                        <span class="mr-1">{{ n }}+</span>
                        <i class="fas fa-star text-yellow-400"></i>
                    </label>
                </div>
            </div>
        </div>

        <!-- Clear Filters -->
        <button
            @click="clearFilters"
            class="w-full bg-gray-200 text-gray-700 py-2 rounded-lg hover:bg-gray-300 transition"
        >
            Clear All Filters
        </button>
    </aside>
</template>
<script>
export default {};
</script>
<style lang=""></style>
