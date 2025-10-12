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
            class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 text-white shadow-xl"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div
                    class="flex flex-col md:flex-row md:items-center md:justify-between"
                >
                    <div class="mb-4 md:mb-0">
                        <h1
                            class="text-2xl md:text-4xl font-bold mb-2 bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent"
                        >
                            {{ __("Products Management") }}
                        </h1>
                        <p
                            class="text-blue-100 text-sm md:text-base opacity-90 max-w-2xl"
                        >
                            {{
                                __(
                                    "Manage your product inventory and catalog with real-time updates and advanced filtering"
                                )
                            }}
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a
                            :href="$page.props.routes.create"
                            class="px-4 py-2 font-medium bg-white/20 cursor-pointer hover:bg-white/30 text-white rounded-lg transition-all duration-300 flex items-center space-x-2 backdrop-blur-sm border border-white/30 hover:border-white/50 shadow-md hover:shadow-lg"
                        >
                            <i class="mdi mdi-edit"></i>
                            {{ __("Create") }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto py-6">
            <!-- Enhanced Filter Section -->
            <div
                class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-6"
            >
                <!-- Filter Header -->
                <div
                    class="bg-gradient-to-r from-gray-50 to-blue-50 border-b border-gray-100"
                >
                    <button
                        @click="filterExpanded = !filterExpanded"
                        class="w-full p-6 flex items-center cursor-pointer justify-between text-left hover:bg-white/50 transition-all duration-300"
                    >
                        <div class="flex items-center space-x-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg"
                            >
                                <i class="fas fa-filter text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg">
                                    {{ filterHeaderText }}
                                </h3>
                                <p class="text-gray-600 text-sm">
                                    {{ __("Filter and search products") }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button
                                @click.stop="showFilterHelp = true"
                                class="w-10 h-10 flex items-center justify-center text-blue-600 hover:text-blue-700 rounded-xl hover:bg-blue-50 transition-colors"
                                :title="__('Filter Help')"
                            >
                                <i class="fas fa-question-circle text-lg"></i>
                            </button>
                            <div
                                class="w-10 h-10 flex items-center justify-center bg-white rounded-xl shadow-sm border border-gray-200"
                            >
                                <i
                                    class="fas fa-chevron-down text-gray-600 transition-transform duration-300"
                                    :class="{ 'rotate-180': filterExpanded }"
                                ></i>
                            </div>
                        </div>
                    </button>
                </div>

                <!-- Filter Content -->
                <div v-if="filterExpanded" class="p-3 bg-white">
                    <form @submit.prevent="getProducts" class="space-y-6">
                        <!-- Search Inputs Grid -->
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
                        >
                            <div
                                v-for="(field, index) in filterFields"
                                :key="index"
                                class="group"
                            >
                                <label
                                    class="text-sm font-semibold text-gray-700 mb-2 flex items-center"
                                >
                                    <i
                                        :class="field.icon"
                                        class="mr-2 text-blue-500"
                                    ></i>
                                    {{ __(field.label) }}
                                </label>
                                <div class="relative">
                                    <i
                                        :class="
                                            field.icon +
                                            ' absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors'
                                        "
                                    ></i>
                                    <input
                                        v-model="search[field.key]"
                                        type="text"
                                        :placeholder="__(field.placeholder)"
                                        class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 hover:border-gray-300 bg-white shadow-sm"
                                        @input="debouncedGetProducts"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Stock and Price Filters -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label
                                    class="text-sm font-semibold text-gray-700 flex items-center"
                                >
                                    <i
                                        class="fas fa-boxes mr-2 text-blue-500"
                                    ></i>
                                    {{ __("Stock Level") }}
                                </label>
                                <div
                                    class="flex space-x-3 bg-gray-50 p-3 rounded-xl"
                                >
                                    <select
                                        v-model="search.stock_operator"
                                        class="w-24 px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white shadow-sm"
                                        @change="getProducts"
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
                                        v-model="search.stock"
                                        type="number"
                                        min="0"
                                        class="flex-1 px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 shadow-sm"
                                        :placeholder="__('Quantity')"
                                        @input="debouncedGetProducts"
                                    />
                                </div>
                            </div>

                            <div class="space-y-3">
                                <label
                                    class="text-sm font-semibold text-gray-700 flex items-center"
                                >
                                    <i
                                        class="fas fa-dollar-sign mr-2 text-green-500"
                                    ></i>
                                    {{ __("Price Range") }}
                                </label>
                                <div
                                    class="flex space-x-3 bg-gray-50 p-3 rounded-xl"
                                >
                                    <select
                                        v-model="search.price_operator"
                                        class="w-24 px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white shadow-sm"
                                        @change="getProducts"
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
                                        v-model="search.price"
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        class="flex-1 px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 shadow-sm"
                                        :placeholder="__('Amount')"
                                        @input="debouncedGetProducts"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Active Filters -->
                        <div
                            v-if="activeFilterCount > 0"
                            class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100"
                        >
                            <label
                                class="text-sm font-semibold text-gray-700 mb-3 flex items-center"
                            >
                                <i class="fas fa-filter mr-2 text-blue-600"></i>
                                {{ __("Active Filters") }} ({{
                                    activeFilterCount
                                }})
                            </label>
                            <div class="flex flex-wrap gap-2">
                                <span
                                    v-for="(value, key) in activeFilters"
                                    :key="key"
                                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-white text-blue-700 border border-blue-200 shadow-sm hover:shadow-md transition-shadow"
                                >
                                    <i
                                        class="fas fa-tag mr-2 text-blue-500"
                                    ></i>
                                    {{ filterLabels[key] }}: {{ value }}
                                    <button
                                        @click="clearFilter(key)"
                                        class="ml-3 hover:text-blue-900 transition-colors"
                                    >
                                        <i class="fas fa-times"></i>
                                    </button>
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="flex justify-between items-center pt-6 border-t border-gray-100"
                        >
                            <button
                                type="button"
                                @click="resetFilters"
                                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-300 flex items-center space-x-3 shadow-sm hover:shadow-md"
                            >
                                <i class="fas fa-undo-alt"></i>
                                <span class="font-medium">{{
                                    __("Reset All Filters")
                                }}</span>
                            </button>
                            <div class="text-right">
                                <p class="text-sm text-gray-600 font-medium">
                                    {{ filteredResultsText }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ __("Real-time filtering") }}
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Enhanced Products Table Section -->
            <div
                class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden"
            >
                <!-- Table Header -->
                <div
                    class="px-4 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white"
                >
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between"
                    >
                        <div class="mb-4 sm:mb-0">
                            <h2
                                class="text-xl font-bold text-gray-900 flex items-center"
                            >
                                <i class="fas fa-boxes mr-3 text-blue-600"></i>
                                {{ __("Product Inventory") }}
                            </h2>
                            <p
                                v-if="!loading"
                                class="text-gray-600 mt-1 flex items-center"
                            >
                                <i
                                    class="fas fa-chart-bar mr-2 text-green-500"
                                ></i>
                                {{ __("Managing") }}
                                <span
                                    class="font-semibold text-gray-900 mx-1"
                                    >{{ pagination.rowsNumber }}</span
                                >
                                {{ __("products") }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a
                                :href="$page.props.routes.create"
                                class="px-4 py-2 hidden font-medium bg-white/20 cursor-pointer hover:bg-white/30 text-shadow-gray-400 rounded-lg transition-all duration-300 lg:flex items-center space-x-2 backdrop-blur-sm border border-gray-300 shadow-md hover:shadow-lg"
                            >
                                <i class="mdi mdi-edit"></i>
                                {{ __("Create") }}
                            </a>

                            <div
                                class="flex items-center space-x-2 bg-white rounded-xl px-3 py-2 border border-gray-200 shadow-sm"
                            >
                                <i class="fas fa-list-ol text-gray-400"></i>
                                <select
                                    v-model="search.per_page"
                                    @change="getProducts"
                                    class="border-0 focus:ring-0 text-gray-700 font-medium bg-transparent"
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

                <!-- Desktop Table -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                            <tr>
                                <th
                                    v-for="(column, index) in columns"
                                    :key="index"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-gray-200"
                                >
                                    <div class="flex items-center space-x-2">
                                        <i
                                            :class="getColumnIcon(column)"
                                            class="text-blue-500"
                                        ></i>
                                        <span>{{ __(column) }}</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr
                                v-for="product in products"
                                :key="product.id"
                                class="hover:bg-blue-50/50 transition-all duration-300 group"
                            >
                                <!-- Product Name -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center"
                                        >
                                            <i
                                                class="fas fa-box text-blue-600"
                                            ></i>
                                        </div>
                                        <div>
                                            <div
                                                class="font-semibold text-gray-900 group-hover:text-blue-700 transition-colors"
                                            >
                                                {{ product.name }}
                                            </div>
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-purple-100 text-purple-800 mt-1"
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
                                        <span class="font-bold text-green-600">
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
                                                ? 'bg-green-100 text-green-800 border-green-200'
                                                : 'bg-red-100 text-red-800 border-red-200'
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
                                                ? 'bg-yellow-100 text-yellow-800 border-yellow-200'
                                                : 'bg-gray-100 text-gray-600 border-gray-200'
                                        "
                                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium border transition-all duration-300"
                                    >
                                        <i
                                            :class="
                                                product.featured
                                                    ? 'fas fa-star mr-2 text-yellow-500'
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
                                        <a
                                            :href="product.links.edit"
                                            class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white rounded-lg transition-all duration-300 flex items-center space-x-2 shadow-sm hover:shadow-md"
                                        >
                                            <i class="fas fa-edit"></i>
                                            <span>{{ __("Edit") }}</span>
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

                <!-- Mobile Cards -->
                <div class="lg:hidden space-y-4 p-6">
                    <div
                        v-for="product in products"
                        :key="product.id"
                        class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300"
                    >
                        <!-- Header -->
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center"
                                    >
                                        <i class="fas fa-box text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900">
                                            {{ product.name }}
                                        </h3>
                                        <p class="text-gray-600 text-sm">
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
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-500 font-semibold">
                                    {{ __("Price") }}
                                </p>
                                <p class="font-bold text-green-600">
                                    {{ product.currency }}
                                    {{ product.format_price }}
                                </p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-500 font-semibold">
                                    {{ __("Status") }}
                                </p>
                                <div class="flex items-center mt-1">
                                    <i
                                        :class="
                                            product.published
                                                ? 'fas fa-eye text-green-500'
                                                : 'fas fa-eye-slash text-red-500'
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
                            class="flex justify-end space-x-3 pt-4 border-t border-gray-100"
                        >
                            <a
                                :href="product.links.edit"
                                class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white rounded-lg transition-all duration-300 flex items-center space-x-2 shadow-sm hover:shadow-md"
                            >
                                <i class="fas fa-edit"></i>
                                <span>{{ __("Edit") }}</span>
                            </a>
                            <v-delete :item="product" @deleted="getProducts" />
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div
                    v-if="products.length === 0 && !loading"
                    class="text-center py-16"
                >
                    <div
                        class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg"
                    >
                        <i class="fas fa-box-open text-gray-400 text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">
                        {{ __("No products found") }}
                    </h3>
                    <p class="text-gray-600 mb-6 max-w-md mx-auto">
                        {{
                            activeFilterCount > 0
                                ? __(
                                      "Try adjusting your filters to see more results"
                                  )
                                : __(
                                      "Get started by adding your first product to the catalog"
                                  )
                        }}
                    </p>
                    <div class="flex justify-center space-x-4">
                        <button
                            v-if="activeFilterCount > 0"
                            @click="resetFilters"
                            class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300 flex items-center space-x-2 shadow-lg hover:shadow-xl"
                        >
                            <i class="fas fa-filter-circle-xmark"></i>
                            <span class="font-semibold">{{
                                __("Reset Filters")
                            }}</span>
                        </button>
                        <!--
                        <VCreate v-else @created="getProducts" />
                        -->
                    </div>
                </div>

                <!-- Loading State -->
                <div
                    v-if="loading"
                    class="flex justify-center items-center py-20"
                >
                    <div class="text-center">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg"
                        >
                            <i
                                class="fas fa-spinner fa-spin text-blue-600 text-2xl"
                            ></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            {{ __("Loading products") }}
                        </h3>
                        <p class="text-gray-600">
                            {{
                                __("Please wait while we fetch your inventory")
                            }}
                        </p>
                    </div>
                </div>

                <!-- Pagination -->
                <div
                    v-if="products.length > 0"
                    class="px-6 py-4 border-t border-gray-100 bg-gray-50/50"
                >
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between"
                    >
                        <p class="text-gray-700 mb-4 sm:mb-0 flex items-center">
                            <i class="fas fa-chart-pie mr-2 text-blue-500"></i>
                            {{ __("Showing") }}
                            <span class="font-bold text-gray-900 mx-1">{{
                                paginationStart
                            }}</span>
                            {{ __("to") }}
                            <span class="font-bold text-gray-900 mx-1">{{
                                paginationEnd
                            }}</span>
                            {{ __("of") }}
                            <span class="font-bold text-blue-600 mx-1">{{
                                pagination.rowsNumber
                            }}</span>
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

        <!-- Enhanced Filter Help Modal -->
        <div
            v-if="showFilterHelp"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50"
            @click="showFilterHelp = false"
        >
            <div
                class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-95 hover:scale-100"
                @click.stop
            >
                <div
                    class="bg-gradient-to-r from-blue-600 to-purple-700 text-white px-8 py-6"
                >
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl font-bold flex items-center">
                            <i class="fas fa-life-ring mr-3"></i>
                            {{ __("Filter Help Guide") }}
                        </h3>
                        <button
                            @click="showFilterHelp = false"
                            class="w-10 h-10 flex items-center justify-center bg-white/20 hover:bg-white/30 rounded-xl transition-colors"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="p-8 space-y-6 overflow-y-auto max-h-[60vh]">
                    <!-- Operators Section -->
                    <div
                        class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-6"
                    >
                        <h4
                            class="font-bold text-gray-900 text-lg mb-4 flex items-center"
                        >
                            <i class="fas fa-filter mr-3 text-blue-600"></i>
                            {{ __("Filter Operators") }}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div
                                v-for="(op, index) in operatorExamples"
                                :key="index"
                                class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm border border-gray-200"
                            >
                                <code
                                    class="font-mono bg-gray-100 px-2 py-1 rounded text-sm"
                                    >{{ op.symbol }}</code
                                >
                                <span class="text-gray-700 font-medium">{{
                                    __(op.description)
                                }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tips Section -->
                    <div
                        class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl p-6"
                    >
                        <h4
                            class="font-bold text-gray-900 text-lg mb-4 flex items-center"
                        >
                            <i
                                class="fas fa-lightbulb mr-3 text-yellow-500"
                            ></i>
                            {{ __("Tips & Best Practices") }}
                        </h4>
                        <ul class="space-y-3">
                            <li
                                v-for="(tip, index) in helpTips"
                                :key="index"
                                class="flex items-start"
                            >
                                <i
                                    class="fas fa-check-circle text-green-500 mt-1 mr-3"
                                ></i>
                                <span class="text-gray-700">{{ __(tip) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div
                    class="px-8 py-6 bg-gray-50 border-t border-gray-200 flex justify-end"
                >
                    <button
                        @click="showFilterHelp = false"
                        class="px-8 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl"
                    >
                        {{ __("Got It") }}
                    </button>
                </div>
            </div>
        </div>
    </v-admin-layout>
</template>

<script>
import VCreate from "./Create.vue";
import VDelete from "./Delete.vue";
import VAdminLayout from "../../Components/VAdminLayout.vue";
import VPaginate from "../../Components/VPaginate.vue";

export default {
    components: {
        VCreate,
        VDelete,
        VAdminLayout,
        VPaginate,
    },

    data() {
        return {
            filterExpanded: false,
            loading: false,
            showFilterHelp: false,
            products: [],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
                name: "",
                category: "",
                model: "",
                family: "",
                stock: "",
                stock_operator: "=",
                price: "",
                price_operator: "=",
                order_by: "updated_at",
                order_type: "desc",
            },
            operators: [
                { label: "=", value: "=" },
                { label: ">", value: ">" },
                { label: ">=", value: ">=" },
                { label: "<", value: "<" },
                { label: "<=", value: "<=" },
            ],
            columns: [
                "Product",
                "Stock",
                "Price",
                "Published",
                "Featured",
                "Actions",
            ],
            filterLabels: {
                name: __("Name"),
                category: __("Category"),
                model: __("Model"),
                family: __("Family"),
                stock: __("Stock"),
                price: __("Price"),
            },
            filterFields: [
                {
                    key: "name",
                    label: "Product Name",
                    icon: "fas fa-tag",
                    placeholder: "Search by name",
                },
                {
                    key: "category",
                    label: "Category",
                    icon: "fas fa-folder",
                    placeholder: "Search by category",
                },
                {
                    key: "model",
                    label: "Model",
                    icon: "fas fa-cube",
                    placeholder: "Search by model",
                },
                {
                    key: "family",
                    label: "Family",
                    icon: "fas fa-users",
                    placeholder: "Search by family",
                },
            ],
            operatorExamples: [
                { symbol: "=", description: "Equal to" },
                { symbol: ">", description: "Greater than" },
                { symbol: ">=", description: "Greater than or equal to" },
                { symbol: "<", description: "Less than" },
                { symbol: "<=", description: "Less than or equal to" },
            ],
            helpTips: [
                "Text fields support partial matches and are case-insensitive",
                "Empty filter fields are automatically ignored in the search",
                "Use the reset button to quickly clear all active filters",
                "Stock and price filters work with numeric comparisons",
                "Combining multiple filters narrows down results further",
                "Real-time filtering updates results as you type",
            ],
            debounceTimeout: null,
        };
    },

    computed: {
        pagination() {
            return {
                page: this.search.page,
                rowsPerPage: this.search.per_page,
                rowsNumber: this.pages.total_pages * this.search.per_page,
            };
        },
        paginationStart() {
            return (this.search.page - 1) * this.search.per_page + 1;
        },
        paginationEnd() {
            const end = this.search.page * this.search.per_page;
            return end > this.pagination.rowsNumber
                ? this.pagination.rowsNumber
                : end;
        },
        filterHeaderText() {
            const count = this.activeFilterCount;
            return count > 0
                ? `${__("Active Filters")} (${count})`
                : __("Filter Products");
        },
        activeFilterCount() {
            return Object.keys(this.activeFilters).length;
        },
        activeFilters() {
            const filters = {};
            if (this.search.name) filters.name = this.search.name;
            if (this.search.category) filters.category = this.search.category;
            if (this.search.model) filters.model = this.search.model;
            if (this.search.family) filters.family = this.search.family;
            if (this.search.stock)
                filters.stock = `${this.search.stock_operator} ${this.search.stock}`;
            if (this.search.price)
                filters.price = `${this.search.price_operator} ${this.search.price}`;
            return filters;
        },
        filteredResultsText() {
            if (this.loading) return __("Loading...");
            if (this.products.length === 0)
                return __("No products match your filters");
            return __(
                `${this.products.length} of ${this.pagination.rowsNumber} products shown`
            );
        },
    },

    created() {
        this.getProducts();
    },

    watch: {
        "search.per_page"(val) {
            this.search.page = 1;
            this.getProducts();
        },
    },

    methods: {
        getStockBadgeClass(stock) {
            if (stock === null || stock === undefined)
                return "bg-gray-200 text-gray-700";
            if (stock > 50)
                return "bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg";
            if (stock > 10)
                return "bg-gradient-to-r from-yellow-500 to-yellow-600 text-white shadow-lg";
            if (stock > 0)
                return "bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg";
            return "bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg";
        },

        getStockIcon(stock) {
            if (stock === null || stock === undefined)
                return "fas fa-question-circle";
            if (stock > 50) return "fas fa-boxes";
            if (stock > 10) return "fas fa-box";
            if (stock > 0) return "fas fa-exclamation-triangle";
            return "fas fa-times-circle";
        },

        getColumnIcon(column) {
            const icons = {
                Product: "fas fa-tag",
                Stock: "fas fa-boxes",
                Price: "fas fa-dollar-sign",
                Published: "fas fa-eye",
                Featured: "fas fa-star",
                Actions: "fas fa-cog",
            };
            return icons[column] || "fas fa-circle";
        },

        onTableRequest(props) {
            this.search.page = props.pagination.page;
            this.search.per_page = props.pagination.rowsPerPage;
            this.search.order_by = props.pagination.sortBy;
            this.search.order_type = props.pagination.descending
                ? "desc"
                : "asc";
            this.getProducts();
        },

        clearFilter(key) {
            if (key === "stock") {
                this.search.stock = "";
                this.search.stock_operator = "=";
            } else if (key === "price") {
                this.search.price = "";
                this.search.price_operator = "=";
            } else {
                this.search[key] = "";
            }
            this.getProducts();
        },

        resetFilters() {
            this.search = {
                ...this.search,
                name: "",
                category: "",
                model: "",
                family: "",
                stock: "",
                stock_operator: "=",
                price: "",
                price_operator: "=",
                page: 1,
            };
            this.getProducts();
        },

        debouncedGetProducts() {
            // Clear existing timeout
            if (this.debounceTimeout) {
                clearTimeout(this.debounceTimeout);
            }

            // Set new timeout
            this.debounceTimeout = setTimeout(() => {
                this.getProducts();
            }, 500); // 500ms debounce
        },

        async getProducts() {
            this.loading = true;
            try {
                const { data } = await this.$server.get(
                    this.$page.props.routes["products"],
                    {
                        params: this.search,
                    }
                );

                this.products = data.data;
                this.pages = data.meta.pagination;
                this.search.page = data.meta.pagination.current_page;
            } catch (e) {
                if (e?.response?.data?.message) {
                     $notify(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

<style scoped>
/* Smooth transitions for all interactive elements */
button,
input,
select,
.transition-all {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Custom scrollbar for modal */
.modal-content::-webkit-scrollbar {
    width: 6px;
}

.modal-content::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.modal-content::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.modal-content::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Gradient text effect */
.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Hover effects for cards */
.hover-lift:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

/* Enhanced responsive design */
@media (max-width: 768px) {
    .mobile-card {
        margin: 0.5rem;
        padding: 1rem;
    }

    .mobile-stats {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
}
</style>
