<!--
Copyright (c) 2025 Elvis Yerel Roman Concha

This file is part of an open source project licensed under the
"NON-COMMERCIAL USE LICENSE - OPEN SOURCE PROJECT" (Effective Date: 2025-08-03).

You may use, study, modify, and redistribute this file for personal,
educational, or non-commercial research purposes only.

Commercial use is strictly prohibited without prior written consent
from the author.

Combining this software with any project licensed for commercial use
(such as AGPL) is not permitted without explicit authorization.

This software supports OAuth 2.0 and OpenID Connect.

Author Contact: yerel9212@yahoo.es

SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project
-->
<template>
    <div class="hidden sm:flex flex-1 mx-2 max-w-2xl relative">
        <div class="flex w-full">
            <!-- Categories Dropdown Button -->
            <div class="relative hidden md:inline-block">
                <button
                    class="bg-primary-700 cursor-pointer text-white py-1 px-1 rounded-l-lg hover:bg-primary-800 transition flex items-center"
                    @click="toggleCategories"
                >
                    <span class="lg:hidden">
                        <i class="mdi mdi-format-list-bulleted"></i>
                    </span>
                    <i class="mdi mdi-menu ml-2 text-4xl"></i>
                </button>

                <div
                    class="absolute left-0 top-full mt-2 w-full lg:w-4xl overflow-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg z-50"
                    v-if="categoriesOpen"
                >
                    <div
                        class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 flex justify-between"
                    >
                        <h3
                            class="font-semibold text-lg text-gray-900 dark:text-white"
                        >
                            {{ __("All Categories") }}
                        </h3>
                        <button
                            @click="toggleCategories"
                            class="text-gray-700 dark:text-gray-300 font-bold rounded-full w-8 h-8 hover:bg-red-500 hover:text-white cursor-pointer"
                        >
                            X
                        </button>
                    </div>
                    <v-categories />
                </div>
            </div>

            <!-- Search Input (Trigger) -->
            <div
                class="relative flex items-center w-full rounded-r-lg border border-gray-300 dark:border-gray-600 overflow-hidden"
            >
                <input
                    type="text"
                    :placeholder="__('Search products, brands and more...')"
                    class="py-2 px-4 w-full focus:outline-none bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                    v-model="q"
                    @focus="openModal"
                />
                <button
                    class="bg-primary-600 text-white py-2 px-4 hover:bg-primary-700 transition"
                    @click="goToSearch"
                >
                    <i class="mdi mdi-magnify"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Button -->
    <button
        class="h-10 w-10 text-gray-200 bg-purple-700 rounded-full hover:text-white relative cursor-pointer sm:hidden"
        @click="openModal"
    >
        <i class="fas fa-search text-xl"></i>
    </button>

    <!-- Search Modal -->
    <div
        v-if="searchModalOpen"
        class="fixed inset-0 z-50 flex items-start justify-center pt-20 px-4 bg-black/50 backdrop-blur-sm"
        @click.self="closeModal"
    >
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-2xl p-6 animate-fadeIn"
        >
            <!-- Search Input -->
            <div class="relative">
                <i
                    class="mdi mdi-magnify absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500"
                ></i>
                <input
                    ref="searchInput"
                    type="text"
                    v-model="q"
                    :placeholder="__('Search products, brands and more...')"
                    class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 outline-none transition text-gray-800 dark:text-white bg-white dark:bg-gray-700 placeholder-gray-500 dark:placeholder-gray-400"
                    @keyup.enter="search"
                    @keydown.down.prevent="moveSelection(1)"
                    @keydown.up.prevent="moveSelection(-1)"
                />
            </div>

            <!-- Search Results -->
            <div
                v-if="searchResults.length > 0"
                class="mt-4 max-h-96 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700"
            >
                <div
                    v-for="(product, index) in searchResults"
                    :key="product.id"
                    class="py-3"
                >
                    <a
                        :href="product.links?.show"
                        class="flex items-center gap-4 p-3 rounded-lg transition"
                        :class="{
                            'bg-gray-200 dark:bg-gray-700':
                                index === selectedIndex,
                            'hover:bg-gray-100 dark:hover:bg-gray-700':
                                index !== selectedIndex,
                        }"
                    >
                        <div
                            class="w-16 h-16 bg-gray-100 dark:bg-gray-600 rounded-lg overflow-hidden flex-shrink-0"
                        >
                            <img
                                :src="product.images[0]?.url"
                                :alt="product.name"
                                class="w-full h-full object-cover"
                            />
                        </div>
                        <div class="flex-1 text-left">
                            <h3
                                class="text-sm font-medium text-gray-900 dark:text-white"
                            >
                                {{ product.name }}
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ product.category?.name }}
                            </p>
                            <p
                                class="text-sm font-bold text-primary-700 dark:text-primary-400"
                            >
                                {{ product.symbol }} {{ product.format_price }}
                            </p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- No Results -->
            <div
                v-else
                class="text-center py-8 text-gray-500 dark:text-gray-400"
            >
                <i class="mdi mdi-magnify text-3xl mb-2 block"></i>
                <p>{{ __("No results found") }}</p>
            </div>
        </div>
    </div>
</template>

<script>
import VCategories from "./VCategories.vue";
export default {
    components: {
        VCategories,
    },

    data() {
        return {
            categoriesOpen: false,
            q: "",
            searchResults: [],
            searchModalOpen: false,
            mobileSearchOpen: false,
            mobileMenuOpen: false,
            selectedIndex: -1,
        };
    },

    watch: {
        q(value) {
            this.performSearch(value);
            this.selectedIndex = -1;
        },
    },

    methods: {
        getParams() {
            return Object.fromEntries(
                new URLSearchParams(window.location.search)
            );
        },

        search() {
            const params = this.getParams();
            if (this.q) {
                params.q = this.q;
            }

            if (
                this.selectedIndex >= 0 &&
                this.searchResults[this.selectedIndex]
            ) {
                window.location.href =
                    this.searchResults[this.selectedIndex].links?.show;
            } else {
                const queryString = new URLSearchParams(params).toString();
                const url = `${this.$page.props.routes.search}${
                    queryString ? "?" + queryString : ""
                }`;
                window.location.href = url;
            }
        },

        async performSearch(query) {
            try {
                const res = await this.$server.get(
                    this.$page.props.api.ecommerce.search,
                    { params: { q: query, limit: 10 } }
                );

                if (res.status === 200) {
                    this.searchResults = res.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            }
        },

        toggleCategories() {
            this.categoriesOpen = !this.categoriesOpen;
        },

        closeCategories() {
            setTimeout(() => (this.categoriesOpen = false), 200);
        },

        async openModal() {
            await this.performSearch(this.q);
            this.searchModalOpen = true;
            this.$nextTick(() => {
                this.$refs.searchInput.focus();
            });
        },

        closeModal() {
            this.searchModalOpen = false;
            this.q = "";
            this.searchResults = [];
            this.selectedIndex = -1;
        },

        moveSelection(direction) {
            if (this.searchResults.length === 0) return;

            const maxIndex = this.searchResults.length - 1;
            let newIndex = this.selectedIndex + direction;

            if (newIndex < 0) newIndex = maxIndex;
            if (newIndex > maxIndex) newIndex = 0;

            this.selectedIndex = newIndex;
        },
    },
};
</script>

<style>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fadeIn {
    animation: fadeIn 0.3s ease-out;
}
</style>
