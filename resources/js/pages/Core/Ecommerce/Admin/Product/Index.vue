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
        <!-- Enhanced Header Section -->
        <div
            class="bg-linear-to-r from-blue-600 via-purple-600 to-indigo-700 text-white shadow-xl dark:from-blue-800 dark:via-purple-800 dark:to-indigo-900"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div
                    class="flex flex-col md:flex-row md:items-center md:justify-between"
                >
                    <div class="mb-4 md:mb-0">
                        <h1
                            class="text-2xl md:text-4xl font-bold mb-2 bg-linear-to-r from-white to-blue-100 bg-clip-text text-transparent"
                        >
                            {{ __("Products Management") }}
                        </h1>
                        <p
                            class="text-blue-100 dark:text-blue-200 text-sm md:text-base opacity-90 max-w-2xl"
                        >
                            {{
                                __(
                                    "Manage your product inventory and catalog with real-time updates and advanced filtering"
                                )
                            }}
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <button
                            @click="router.visit($page.props.routes.create)"
                            class="px-4 py-2 font-medium bg-white/20 cursor-pointer hover:bg-white/30 text-white rounded-lg transition-all duration-300 flex items-center space-x-2 backdrop-blur-sm border border-white/30 hover:border-white/50 shadow-md hover:shadow-lg"
                        >
                            <i class="mdi mdi-edit"></i>
                            {{ __("Create") }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto py-6 px-2">
            <!-- Enhanced Filter Section -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 mb-6 transition-colors duration-300"
            >
                <div class="p-6">
                    <h3
                        class="text-lg font-bold text-gray-900 dark:text-white flex items-center mb-4"
                    >
                        <i
                            class="fas fa-filter mr-2 text-blue-600 dark:text-blue-400"
                        ></i>
                        {{ __("Filter Products") }}
                    </h3>

                    <!-- Filter Form -->
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
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
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors"
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
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors"
                            />
                        </div>

                        <!-- Stock Filter -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                            >
                                {{ __("Stock") }}
                            </label>
                            <div class="flex space-x-2">
                                <select
                                    v-model="search.stock_operator"
                                    @change="getProducts"
                                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors"
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
                                    :placeholder="__('Stock quantity...')"
                                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors"
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
                            <div class="flex space-x-2">
                                <select
                                    v-model="search.price_operator"
                                    @change="getProducts"
                                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors"
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
                                    :placeholder="__('Price amount...')"
                                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors"
                                />
                            </div>
                        </div>

                        <!-- Reset Button -->
                        <div class="flex items-end">
                            <button
                                @click="resetFilters"
                                class="w-full px-4 py-2 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-800/30 text-red-700 dark:text-red-300 rounded-lg transition-all duration-300 flex items-center justify-center space-x-2"
                            >
                                <i class="fas fa-redo"></i>
                                <span>{{ __("Reset Filters") }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid/Table Section -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors duration-300"
            >
                <!-- Table Header -->
                <div
                    class="px-4 py-4 border-b border-gray-100 dark:border-gray-600 bg-linear-to-r from-gray-50 to-white dark:from-gray-700 dark:to-gray-800"
                >
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between"
                    >
                        <div class="mb-4 sm:mb-0">
                            <h2
                                class="text-xl font-bold text-gray-900 dark:text-white flex items-center"
                            >
                                <i
                                    class="fas fa-boxes mr-3 text-blue-600 dark:text-blue-400"
                                ></i>
                                {{ __("Product Inventory") }}
                            </h2>
                            <p
                                v-if="!loading"
                                class="text-gray-600 dark:text-gray-300 mt-1 flex items-center"
                            >
                                <i
                                    class="fas fa-chart-bar mr-2 text-green-500 dark:text-green-400"
                                ></i>
                                {{ __("Showing") }}
                                <span
                                    class="font-semibold text-gray-900 dark:text-white mx-1"
                                    >{{ products.length }}</span
                                >
                                {{ __("products") }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button
                                @click="router.visit($page.props.routes.create)"
                                class="px-4 py-2 font-medium bg-white/20 cursor-pointer hover:bg-white/30 text-white rounded-lg transition-all duration-300 flex items-center space-x-2 backdrop-blur-sm border border-white/30 hover:border-white/50 shadow-md hover:shadow-lg"
                            >
                                <i class="mdi mdi-edit"></i>
                                {{ __("Create") }}
                            </button>
                            <div
                                class="flex items-center space-x-2 bg-white dark:bg-gray-600 rounded-xl px-3 py-2 border border-gray-200 dark:border-gray-500 shadow-sm"
                            >
                                <i
                                    class="fas fa-list-ol text-gray-400 dark:text-gray-300"
                                ></i>
                                <select
                                    v-model="search.per_page"
                                    @change="getProducts"
                                    class="border-0 focus:ring-0 text-gray-700 dark:text-gray-300 font-medium bg-transparent"
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
                                    <option value="100">
                                        100 {{ __("per page") }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile View - 1 Column -->
                <div class="block md:hidden space-y-4 p-4">
                    <div
                        v-for="product in products"
                        :key="product.id"
                        class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all duration-300"
                    >
                        <!-- Header -->
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <div
                                        class="w-10 h-10 bg-linear-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl flex items-center justify-center"
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
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold shadow-sm"
                            >
                                {{ product.stock }} {{ __("stock") }}
                            </span>
                        </div>

                        <!-- Info Grid -->
                        <div class="grid grid-cols-2 gap-2 mb-3">
                            <div
                                class="bg-gray-50 dark:bg-gray-600 rounded-lg p-2"
                            >
                                <p
                                    class="text-xs text-gray-500 dark:text-gray-400 font-semibold"
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
                                class="bg-gray-50 dark:bg-gray-600 rounded-lg p-2"
                            >
                                <p
                                    class="text-xs text-gray-500 dark:text-gray-400 font-semibold"
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
                                    <span class="text-xs font-medium">
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
                            class="flex justify-end space-x-2 pt-3 border-t border-gray-100 dark:border-gray-500"
                        >
                            <button
                                @click="
                                    router.visit(
                                        `${page.props.routes.index}/${product.id}/edit`
                                    )
                                "
                                class="px-3 py-1.5 bg-blue-500 cursor-pointer hover:bg-blue-700 text-white rounded-lg transition-all duration-300 flex items-center space-x-1 shadow-sm hover:shadow-md text-xs"
                            >
                                <i class="fas fa-edit"></i>
                                <span>{{ __("Edit") }}</span>
                            </button>
                            <v-delete :item="product" @deleted="getProducts" />
                        </div>
                    </div>
                </div>

                <!-- Tablet View - 2 Columns -->
                <div class="hidden md:grid lg:hidden grid-cols-2 gap-4 p-4">
                    <div
                        v-for="product in products"
                        :key="product.id"
                        class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all duration-300"
                    >
                        <!-- Header -->
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <div
                                        class="w-12 h-12 bg-linear-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl flex items-center justify-center"
                                    >
                                        <i
                                            class="fas fa-box text-blue-600 dark:text-blue-400"
                                        ></i>
                                    </div>
                                    <div>
                                        <h3
                                            class="font-bold text-gray-900 dark:text-white"
                                        >
                                            {{ product.name }}
                                        </h3>
                                        <p
                                            class="text-gray-600 dark:text-gray-300 text-sm"
                                        >
                                            {{ product.category?.name }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <span
                                :class="getStockBadgeClass(product.stock)"
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm"
                            >
                                {{ product.stock }} {{ __("in stock") }}
                            </span>
                        </div>

                        <!-- Info Grid -->
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div
                                class="bg-gray-50 dark:bg-gray-600 rounded-lg p-3"
                            >
                                <p
                                    class="text-xs text-gray-500 dark:text-gray-400 font-semibold"
                                >
                                    {{ __("Price") }}
                                </p>
                                <p
                                    class="font-bold text-green-600 dark:text-green-400"
                                >
                                    {{ product.currency }}
                                    {{ product.format_price }}
                                </p>
                            </div>
                            <div
                                class="bg-gray-50 dark:bg-gray-600 rounded-lg p-3"
                            >
                                <p
                                    class="text-xs text-gray-500 dark:text-gray-400 font-semibold"
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
                                        class="mr-2"
                                    ></i>
                                    <span class="text-sm font-medium">
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
                            class="flex justify-end space-x-3 pt-4 border-t border-gray-100 dark:border-gray-500"
                        >
                            <button
                                @click="
                                    router.visit(
                                        `${page.props.routes.index}/${product.id}/edit`
                                    )
                                "
                                class="px-4 py-2 cursor-pointer bg-blue-500 hover:bg-blue-700 text-white rounded-lg transition-all duration-300 flex items-center space-x-2 shadow-sm hover:shadow-md"
                            >
                                <i class="fas fa-edit"></i>
                                <span>{{ __("Edit") }}</span>
                            </button>
                            <v-delete :item="product" @deleted="getProducts" />
                        </div>
                    </div>
                </div>

                <!-- Desktop Table - LG and above -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="w-full">
                        <thead
                            class="bg-linear-to-r from-gray-50 to-blue-50 dark:from-gray-700 dark:to-blue-900/30"
                        >
                            <tr>
                                <th
                                    v-for="(column, index) in columns"
                                    :key="index"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200 dark:border-gray-600"
                                >
                                    <div class="flex items-center space-x-2">
                                        <i
                                            :class="getColumnIcon(column)"
                                            class="text-blue-500 dark:text-blue-400"
                                        ></i>
                                        <span>{{ __(column) }}</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-gray-100 dark:divide-gray-600"
                        >
                            <tr
                                v-for="product in products"
                                :key="product.id"
                                class="hover:bg-blue-50/50 dark:hover:bg-blue-900/20 transition-all duration-300 group"
                            >
                                <!-- Product Name -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="w-10 h-10 bg-linear-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 rounded-lg flex items-center justify-center"
                                        >
                                            <i
                                                class="fas fa-box text-blue-600 dark:text-blue-400"
                                            ></i>
                                        </div>
                                        <div>
                                            <div
                                                class="font-semibold text-gray-900 dark:text-white group-hover:text-blue-700 dark:group-hover:text-blue-300 transition-colors"
                                            >
                                                {{ product.name }}
                                            </div>
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 mt-1"
                                            >
                                                <i
                                                    class="fas fa-tag text-xs mr-1"
                                                ></i>
                                                {{ product.category.name }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Stock Level -->
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-center"
                                >
                                    <span
                                        :class="
                                            getStockBadgeClass(product.stock)
                                        "
                                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold shadow-sm transition-all duration-300"
                                    >
                                        <i
                                            :class="getStockIcon(product.stock)"
                                            class="mr-2"
                                        ></i>
                                        {{ product.stock }}
                                        <span class="ml-1 text-xs opacity-90">{{
                                            __("units")
                                        }}</span>
                                    </span>
                                </td>

                                <!-- Price -->
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-right"
                                >
                                    <div class="flex flex-col items-end">
                                        <span
                                            class="font-bold text-green-600 dark:text-green-400"
                                        >
                                            {{ product.currency }}
                                            {{ product.format_price }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Published -->
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-center"
                                >
                                    <span
                                        :class="
                                            product.published
                                                ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border-green-200 dark:border-green-700'
                                                : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border-red-200 dark:border-red-700'
                                        "
                                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium border transition-all duration-300"
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
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-center"
                                >
                                    <span
                                        :class="
                                            product.featured
                                                ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border-yellow-200 dark:border-yellow-700'
                                                : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 border-gray-200 dark:border-gray-600'
                                        "
                                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium border transition-all duration-300"
                                    >
                                        <i
                                            :class="
                                                product.featured
                                                    ? 'fas fa-star mr-2 text-yellow-500 dark:text-yellow-400'
                                                    : 'far fa-star mr-2'
                                            "
                                        ></i>
                                        {{
                                            product.featured
                                                ? __("Featured")
                                                : __("Standard")
                                        }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-right"
                                >
                                    <div class="flex justify-end space-x-2">
                                        <button
                                            @click="
                                                router.visit(
                                                    `${page.props.routes.index}/${product.id}/edit`
                                                )
                                            "
                                            class="px-4 py-2 bg-blue-500 cursor-pointer hover:bg-blue-700 text-white rounded-lg transition-all duration-300 flex items-center space-x-2 shadow-sm hover:shadow-md"
                                        >
                                            <i class="fas fa-edit"></i>
                                            <span>{{ __("Edit") }}</span>
                                        </button>
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
                    class="text-center py-16"
                >
                    <div
                        class="w-24 h-24 bg-linear-to-br from-gray-100 to-gray-200 dark:from-gray-600 dark:to-gray-700 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg"
                    >
                        <i
                            class="fas fa-box-open text-gray-400 dark:text-gray-500 text-4xl"
                        ></i>
                    </div>
                    <h3
                        class="text-lg font-bold text-gray-900 dark:text-white mb-3"
                    >
                        {{ __("No products found") }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        {{ __("Try adjusting your filters") }}
                    </p>
                    <button
                        @click="resetFilters"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-300 flex items-center space-x-2 mx-auto"
                    >
                        <i class="fas fa-redo"></i>
                        <span>{{ __("Reset Filters") }}</span>
                    </button>
                </div>

                <!-- Loading State -->
                <div
                    v-if="loading"
                    class="flex justify-center items-center py-20"
                >
                    <div class="text-center">
                        <div
                            class="w-16 h-16 bg-linear-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg"
                        >
                            <i
                                class="fas fa-spinner fa-spin text-blue-600 dark:text-blue-400 text-2xl"
                            ></i>
                        </div>
                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-white mb-2"
                        >
                            {{ __("Loading products") }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{
                                __("Please wait while we fetch your inventory")
                            }}
                        </p>
                    </div>
                </div>

                <!-- Pagination -->
                <div
                    v-if="products.length > 0"
                    class="px-6 py-4 border-t border-gray-100 dark:border-gray-600 bg-gray-50/50 dark:bg-gray-700/50"
                >
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between"
                    >
                        <p
                            class="text-gray-700 dark:text-gray-300 mb-4 sm:mb-0 flex items-center"
                        >
                            <i
                                class="fas fa-chart-pie mr-2 text-blue-500 dark:text-blue-400"
                            ></i>
                            {{ __("Showing") }}
                            <span
                                class="font-bold text-gray-900 dark:text-white mx-1"
                                >{{
                                    (search.page - 1) * search.per_page + 1
                                }}-{{
                                    Math.min(
                                        search.page * search.per_page,
                                        pages.total
                                    )
                                }}</span
                            >
                            {{ __("of") }}
                            <span
                                class="font-bold text-blue-600 dark:text-blue-400 mx-1"
                                >{{ pages.total }}</span
                            >
                            {{ __("products") }}
                        </p>
                        <div class="flex space-x-2">
                            <v-paginate
                                :total-pages="pages.total_pages"
                                v-model="search.page"
                                @change="getProducts"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </v-admin-layout>
</template>

<script setup>
import VDelete from "./Delete.vue";
import VAdminLayout from "../../Components/VAdminLayout.vue";
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
    if (stock > 50)
        return "bg-linear-to-r from-green-500 to-green-600 text-white shadow-lg";
    if (stock > 10)
        return "bg-linear-to-r from-yellow-500 to-yellow-600 text-white shadow-lg";
    if (stock > 0)
        return "bg-linear-to-r from-orange-500 to-orange-600 text-white shadow-lg";
    return "bg-linear-to-r from-red-500 to-red-600 text-white shadow-lg";
};

const getStockIcon = (stock) => {
    if (stock === null || stock === undefined) return "fas fa-question-circle";
    if (stock > 50) return "fas fa-boxes";
    if (stock > 10) return "fas fa-box";
    if (stock > 0) return "fas fa-exclamation-triangle";
    return "fas fa-times-circle";
};

const getColumnIcon = (column) => {
    const icons = {
        Product: "fas fa-tag",
        Stock: "fas fa-boxes",
        Price: "fas fa-dollar-sign",
        Published: "fas fa-eye",
        Featured: "fas fa-star",
        Actions: "fas fa-cog",
    };
    return icons[column] || "fas fa-circle";
};

const onFilterChange = () => {
    // Reset to first page when filtering
    search.value.page = 1;

    // Debounce the API call
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
        // Clean up empty values from search object
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
