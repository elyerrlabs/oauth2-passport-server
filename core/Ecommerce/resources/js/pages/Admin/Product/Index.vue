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
    <v-admin-layout>
        <!-- Header -->
        <div class="bg-blue-600 dark:bg-blue-800 text-white p-4 sm:p-6">
            <div class="w-full">
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-4"
                >
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold">
                            {{ __("Products Management") }}
                        </h1>
                        <p
                            class="text-blue-100 dark:text-blue-200 text-sm mt-1"
                        >
                            {{
                                __("Manage your product inventory and catalog")
                            }}
                        </p>
                    </div>
                    <div>
                        <button
                            @click="router.visit($page.props.routes.create)"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 text-white rounded-lg transition-colors flex items-center"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            {{ __("Create Product") }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="w-full md:p-6">
            <!-- Filters -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 mb-6"
            >
                <div class="p-2 sm:p-6">
                    <h3
                        class="text-lg font-bold text-gray-900 dark:text-white mb-4"
                    >
                        {{ __("Filter Products") }}
                    </h3>

                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"
                    >
                        <!-- Name Search -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                            >
                                {{ __("Product Name") }}
                            </label>
                            <input
                                type="text"
                                v-model="search.name"
                                @input="onFilterChange"
                                :placeholder="__('Search by name...')"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            />
                        </div>

                        <!-- Category -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                            >
                                {{ __("Category") }}
                            </label>
                            <input
                                type="text"
                                v-model="search.category"
                                @input="onFilterChange"
                                :placeholder="__('Filter by category...')"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            />
                        </div>

                        <!-- Stock Filter -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                            >
                                {{ __("Stock") }}
                            </label>
                            <div class="flex gap-2">
                                <select
                                    v-model="search.stock_operator"
                                    @change="getProducts"
                                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                >
                                    <option
                                        v-for="op in operators"
                                        :key="op.value"
                                        :value="op.value"
                                    >
                                        {{ op.label }}
                                    </option>
                                </select>
                                <input
                                    type="number"
                                    v-model="search.stock"
                                    @input="onFilterChange"
                                    :placeholder="__('Stock')"
                                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                />
                            </div>
                        </div>

                        <!-- Price Filter -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                            >
                                {{ __("Price") }}
                            </label>
                            <div class="flex gap-2">
                                <select
                                    v-model="search.price_operator"
                                    @change="getProducts"
                                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                >
                                    <option
                                        v-for="op in operators"
                                        :key="op.value"
                                        :value="op.value"
                                    >
                                        {{ op.label }}
                                    </option>
                                </select>
                                <input
                                    type="number"
                                    step="0.01"
                                    v-model="search.price"
                                    @input="onFilterChange"
                                    :placeholder="__('Price')"
                                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                />
                            </div>
                        </div>

                        <!-- Reset Button -->
                        <div class="flex items-end">
                            <button
                                @click="resetFilters"
                                class="w-full px-4 py-2 bg-red-100 dark:bg-red-900 hover:bg-red-200 dark:hover:bg-red-800 text-red-700 dark:text-red-300 rounded-lg transition-colors flex items-center justify-center"
                            >
                                <i class="fas fa-redo mr-2"></i>
                                {{ __("Reset Filters") }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700"
            >
                <!-- Header -->
                <div
                    class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-600"
                >
                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-4"
                    >
                        <div>
                            <h2
                                class="text-lg font-bold text-gray-900 dark:text-white"
                            >
                                {{ __("Product Inventory") }}
                            </h2>
                            <p
                                v-if="!loading"
                                class="text-gray-600 dark:text-gray-300 text-sm mt-1"
                            >
                                {{ __("Showing") }} {{ products.length }}
                                {{ __("products") }}
                            </p>
                        </div>
                        <div class="flex items-center gap-4">
                            <select
                                v-model="search.per_page"
                                @change="getProducts"
                                class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                            >
                                <option value="10">
                                    10 {{ __("per page") }}
                                </option>
                                <option value="15">
                                    15 {{ __("per page") }}
                                </option>
                                <option value="25">
                                    25 {{ __("per page") }}
                                </option>
                                <option value="50">
                                    50 {{ __("per page") }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Mobile & Tablet View -->
                <div class="lg:hidden">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4">
                        <div
                            v-for="product in products"
                            :key="product.id"
                            class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4"
                        >
                            <!-- Header -->
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <div class="flex items-start gap-3 mb-2">
                                        <div
                                            class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center"
                                        >
                                            <i
                                                class="fas fa-box text-blue-600 dark:text-blue-400"
                                            ></i>
                                        </div>
                                        <div>
                                            <h3
                                                class="font-bold text-gray-900 dark:text-white text-sm"
                                            >
                                                {{ product.name }}
                                            </h3>
                                            <p
                                                class="text-gray-600 dark:text-gray-300 text-xs"
                                            >
                                                {{ product.category?.name }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <span
                                    :class="getStockBadgeClass(product.stock)"
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold"
                                >
                                    {{ product.stock }}
                                </span>
                            </div>

                            <!-- Info -->
                            <div class="grid grid-cols-2 gap-2 mb-3">
                                <div
                                    class="bg-gray-50 dark:bg-gray-600 rounded p-2"
                                >
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        {{ __("Price") }}
                                    </p>
                                    <p
                                        class="font-bold text-green-600 dark:text-green-400 text-sm"
                                    >
                                        {{ product.currency }}
                                        {{ product.format_price }}
                                    </p>
                                </div>
                                <div
                                    class="bg-gray-50 dark:bg-gray-600 rounded p-2"
                                >
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        {{ __("Status") }}
                                    </p>
                                    <div class="flex items-center mt-1">
                                        <i
                                            :class="
                                                product.published
                                                    ? 'fas fa-eye text-green-500 dark:text-green-400'
                                                    : 'fas fa-eye-slash text-red-500 dark:text-red-400'
                                            "
                                            class="mr-1 text-xs"
                                        ></i>
                                        <span class="text-xs">
                                            {{
                                                product.published
                                                    ? __("Published")
                                                    : __("Hidden")
                                            }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div
                                class="flex justify-end gap-2 pt-3 border-t border-gray-200 dark:border-gray-500"
                            >
                                <button
                                    @click="
                                        router.visit(
                                            `${page.props.routes.index}/${product.id}/edit`
                                        )
                                    "
                                    class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs flex items-center"
                                >
                                    <i class="fas fa-edit mr-1"></i>
                                    {{ __("Edit") }}
                                </button>
                                <v-delete
                                    :item="product"
                                    @deleted="getProducts"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div
                        v-if="products.length === 0 && !loading"
                        class="text-center py-8"
                    >
                        <div
                            class="flex flex-col items-center text-gray-400 dark:text-gray-500"
                        >
                            <i class="fas fa-box-open text-3xl mb-2"></i>
                            <h3
                                class="text-base font-medium text-gray-900 dark:text-white mb-1"
                            >
                                {{ __("No products found") }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __("Try adjusting your filters") }}
                            </p>
                        </div>
                    </div>

                    <!-- Loading -->
                    <div v-if="loading" class="flex justify-center py-8">
                        <div
                            class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 dark:border-blue-400"
                        ></div>
                    </div>
                </div>

                <!-- Desktop Table -->
                <div class="hidden lg:block">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        v-for="(column, index) in columns"
                                        :key="index"
                                        class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase border-b border-gray-200 dark:border-gray-600"
                                    >
                                        {{ __(column) }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-gray-200 dark:divide-gray-600"
                            >
                                <tr
                                    v-for="product in products"
                                    :key="product.id"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                >
                                    <!-- Product -->
                                    <td class="px-4 sm:px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center"
                                            >
                                                <i
                                                    class="fas fa-box text-blue-600 dark:text-blue-400"
                                                ></i>
                                            </div>
                                            <div>
                                                <div
                                                    class="font-medium text-gray-900 dark:text-white"
                                                >
                                                    {{ product.name }}
                                                </div>
                                                <div
                                                    class="text-sm text-gray-500 dark:text-gray-400"
                                                >
                                                    {{ product.category.name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Stock -->
                                    <td class="px-4 sm:px-6 py-4 text-center">
                                        <span
                                            :class="
                                                getStockBadgeClass(
                                                    product.stock
                                                )
                                            "
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                        >
                                            {{ product.stock }}
                                        </span>
                                    </td>

                                    <!-- Price -->
                                    <td class="px-4 sm:px-6 py-4 text-right">
                                        <span
                                            class="font-bold text-green-600 dark:text-green-400"
                                        >
                                            {{ product.currency }}
                                            {{ product.format_price }}
                                        </span>
                                    </td>

                                    <!-- Published -->
                                    <td class="px-4 sm:px-6 py-4 text-center">
                                        <span
                                            :class="
                                                product.published
                                                    ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300'
                                                    : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300'
                                            "
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                        >
                                            <i
                                                :class="
                                                    product.published
                                                        ? 'fas fa-eye mr-2'
                                                        : 'fas fa-eye-slash mr-2'
                                                "
                                            ></i>
                                            {{
                                                product.published
                                                    ? __("Published")
                                                    : __("Hidden")
                                            }}
                                        </span>
                                    </td>

                                    <!-- Featured -->
                                    <td class="px-4 sm:px-6 py-4 text-center">
                                        <span
                                            :class="
                                                product.featured
                                                    ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300'
                                                    : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'
                                            "
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                        >
                                            <i class="fas fa-star mr-2"></i>
                                            {{
                                                product.featured
                                                    ? __("Featured")
                                                    : __("Standard")
                                            }}
                                        </span>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-4 sm:px-6 py-4">
                                        <div class="flex justify-end gap-2">
                                            <a
                                                :href="`${page.props.routes.index}/${product.id}/edit`"
                                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded flex items-center"
                                            >
                                                <i class="fas fa-edit mr-2"></i>
                                                {{ __("Edit") }}
                                            </a>
                                            <v-delete
                                                :item="product"
                                                @deleted="getProducts"
                                            />
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Empty State -->
                    <div
                        v-if="products.length === 0 && !loading"
                        class="text-center py-12"
                    >
                        <div
                            class="flex flex-col items-center text-gray-400 dark:text-gray-500"
                        >
                            <i class="fas fa-box-open text-4xl mb-3"></i>
                            <h3
                                class="text-lg font-medium text-gray-900 dark:text-white mb-1"
                            >
                                {{ __("No products found") }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __("Try adjusting your filters") }}
                            </p>
                        </div>
                    </div>

                    <!-- Loading -->
                    <div v-if="loading" class="flex justify-center py-12">
                        <div
                            class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 dark:border-blue-400"
                        ></div>
                    </div>
                </div>
            </div>
            <!-- Pagination -->
            <v-paginate
                v-show="products.length"
                :total-pages="pages.total_pages"
                v-model="search.page"
                @change="getProducts"
            />
        </div>
    </v-admin-layout>
</template>

<script setup>
import VDelete from "./Delete.vue";
import VAdminLayout from "@Ecommerce/components/VAdminLayout.vue";
import VPaginate from "@/components/VPaginate.vue";
import { usePage, router } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";

const page = usePage();

const loading = ref(false);
const products = ref([]);
const pages = ref({
    total: 0,
    total_pages: 0,
});

const search = ref({
    page: 1,
    per_page: 15,
    name: "",
    category: "",
    stock: "",
    stock_operator: "=",
    price: "",
    price_operator: "=",
});

const operators = ref([
    { label: "=", value: "=" },
    { label: ">", value: ">" },
    { label: ">=", value: ">=" },
    { label: "<", value: "<" },
    { label: "<=", value: "<=" },
]);

const columns = ref([
    "Product",
    "Stock",
    "Price",
    "Published",
    "Featured",
    "Actions",
]);

let filterTimeout = null;

onMounted(async () => {
    await getProducts();
});

const getStockBadgeClass = (stock) => {
    if (stock === null || stock === undefined)
        return "bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300";
    if (stock > 50) return "bg-green-500 text-white";
    if (stock > 10) return "bg-yellow-500 text-white";
    if (stock > 0) return "bg-orange-500 text-white";
    return "bg-red-500 text-white";
};

const onFilterChange = () => {
    search.value.page = 1;

    if (filterTimeout) clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        getProducts();
    }, 300);
};

const resetFilters = () => {
    search.value.name = "";
    search.value.category = "";
    search.value.stock = "";
    search.value.stock_operator = "=";
    search.value.price = "";
    search.value.price_operator = "=";
    search.value.page = 1;

    getProducts();
};

const getProducts = async () => {
    loading.value = true;

    try {
        const cleanSearch = { ...search.value };
        Object.keys(cleanSearch).forEach((key) => {
            if (
                cleanSearch[key] === "" ||
                cleanSearch[key] === null ||
                cleanSearch[key] === undefined
            ) {
                delete cleanSearch[key];
            }
        });

        const res = await $server.get(page.props.api.products, {
            params: cleanSearch,
        });
        if (res.status == 200) {
            const values = res.data;
            products.value = values.data;
            pages.value = values.meta.pagination;
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    } finally {
        loading.value = false;
    }
};
</script>
