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
        <!-- Header Section -->
        <div class="header-section bg-blue-600 text-white p-4 md:p-6">
            <div
                class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4"
            >
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold">
                        {{ __("Categories Management") }}
                    </h1>
                    <p
                        class="text-blue-100 opacity-90 mt-1 text-sm md:text-base"
                    >
                        {{ __("Manage product categories and organization") }}
                    </p>
                </div>
                <div class="w-full md:w-auto">
                    <a
                        :href="$page.props.routes.create"
                        class="py-2 px-4 bg-green-500 cursor-pointer text-gray-100 rounded-full font-medium"
                    >
                        <span class="mdi mdi-plus"></span>
                        {{ __("Create Category") }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="p-4 md:p-6">
            <!-- Search and Filters -->
            <div class="mb-4 md:mb-6">
                <div class="relative">
                    <input
                        v-model="searchTerm"
                        placeholder="Search categories..."
                        class="w-full pl-10 pr-4 py-2 md:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors duration-200"
                        @input="handleSearch"
                    />
                    <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                    >
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Categories Table - Desktop View -->
            <div
                class="bg-white rounded-lg shadow-md overflow-hidden hidden md:block"
            >
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    v-for="(label, index) in columns"
                                    :key="index"
                                    class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    {{ __(label) }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="category in categories"
                                :key="category.id"
                                class="hover:bg-gray-50 transition-colors duration-150"
                            >
                                <!-- Name Column -->
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i
                                            v-if="category.icon?.icon"
                                            :class="`mdi ${category.icon.icon} text-blue-600 mr-2 text-lg`"
                                        ></i>
                                        <span class="font-medium text-gray-900">
                                            {{ category.name }}
                                        </span>
                                    </div>
                                    <span
                                        v-if="category.parent?.id"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"
                                    >
                                        <i
                                            :class="[
                                                'p-2 mdi',
                                                category.parent?.icon?.icon,
                                            ]"
                                        ></i>
                                        {{ category.parent?.name }}
                                    </span>
                                </td>

                                <td class="px-4 md:px-6 py-4 text-center">
                                    <span
                                        class="font-medium bg-green-700 py-1 px-3 rounded-full text-white"
                                    >
                                        {{ category.children.length }}
                                    </span>
                                </td>

                                <!-- Published Column -->
                                <td
                                    class="px-4 md:px-6 py-4 whitespace-nowrap text-center"
                                >
                                    <span
                                        :class="`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                            category.published
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-gray-100 text-gray-800'
                                        }`"
                                    >
                                        <i
                                            :class="`fas ${
                                                category.published
                                                    ? 'fa-check-circle'
                                                    : 'fa-times-circle'
                                            } mr-1`"
                                        ></i>
                                        {{
                                            category.published
                                                ? __("Published")
                                                : "Hidden"
                                        }}
                                    </span>
                                </td>

                                <!-- Featured Column -->
                                <td
                                    class="px-4 md:px-6 py-4 whitespace-nowrap text-center"
                                >
                                    <span
                                        :class="`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                            category.featured
                                                ? 'bg-yellow-100 text-yellow-800'
                                                : 'bg-gray-100 text-gray-800'
                                        }`"
                                    >
                                        <i
                                            :class="`fas ${
                                                category.featured
                                                    ? 'fa-star'
                                                    : 'fa-star-o'
                                            } mr-1`"
                                        ></i>
                                        {{
                                            category.featured
                                                ? __("Featured")
                                                : "Standard"
                                        }}
                                    </span>
                                </td>

                                <!-- Actions Column -->
                                <td
                                    class="px-4 md:px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                >
                                    <div
                                        class="flex items-center justify-end space-x-2"
                                    >
                                        <a
                                            :href="category?.links.edit"
                                            class="py-2 px-4 cursor-pointer bg-green-500 text-gray-100 rounded-full font-medium"
                                        >
                                            <span class="mdi mdi-plus"></span>
                                            {{ __("Edit") }}
                                        </a>
                                        <v-delete
                                            :item="category"
                                            @deleted="getCategories"
                                            class="action-btn"
                                        />
                                    </div>
                                </td>
                            </tr>

                            <!-- Empty State -->
                            <tr v-if="categories.length === 0 && !loading">
                                <td
                                    :colspan="columns.length"
                                    class="px-6 py-12 text-center"
                                >
                                    <div
                                        class="flex flex-col items-center text-gray-400"
                                    >
                                        <i
                                            class="fas fa-folder-open text-4xl mb-3"
                                        ></i>
                                        <h3 class="text-lg font-medium mb-1">
                                            {{ __("No categories found") }}
                                        </h3>
                                        <p class="text-sm">
                                            {{
                                                __(
                                                    "Create your first category to get started"
                                                )
                                            }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Loading State -->
                <div
                    v-if="loading"
                    class="flex items-center justify-center p-12"
                >
                    <div
                        class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"
                    ></div>
                </div>
            </div>

            <!-- Mobile Cards View -->
            <div class="md:hidden space-y-4">
                <div
                    v-for="category in categories"
                    :key="category.id"
                    class="bg-white rounded-lg shadow-md p-4"
                >
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <i
                                v-if="category.icon?.icon"
                                :class="`mdi ${category.icon.icon} text-blue-600 mr-2 text-lg`"
                            ></i>
                            <span class="font-medium text-gray-900">
                                {{ category.name }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a
                                :href="category?.links.edit"
                                class="py-2 px-4 cursor-pointer bg-green-500 text-gray-100 rounded-full font-medium"
                            >
                                <span class="mdi mdi-plus"></span>
                                {{ __("Edit") }}
                            </a>
                            <v-delete
                                :item="category"
                                @deleted="getCategories"
                                class="action-btn"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="flex flex-col">
                            <span class="text-gray-500">{{ __("Slug") }}</span>
                            <span class="font-medium">{{ category.slug }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-gray-500">
                                {{ __("Status") }}
                            </span>
                            <span
                                :class="`inline-flex items-center ${
                                    category.published
                                        ? 'text-green-600'
                                        : 'text-gray-600'
                                }`"
                            >
                                <i
                                    :class="`fas ${
                                        category.published
                                            ? 'fa-check-circle'
                                            : 'fa-times-circle'
                                    } mr-1`"
                                ></i>
                                {{
                                    category.published
                                        ? __("Published")
                                        : "Hidden"
                                }}
                            </span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-gray-500">
                                {{ __("Featured") }}
                            </span>
                            <span
                                :class="`inline-flex items-center ${
                                    category.featured
                                        ? 'text-yellow-600'
                                        : 'text-gray-600'
                                }`"
                            >
                                <i
                                    :class="`fas ${
                                        category.featured
                                            ? 'fa-star'
                                            : 'fa-star-o'
                                    } mr-1`"
                                ></i>
                                {{ category.featured ? "Yes" : "No" }}
                            </span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-gray-500">{{ __("Icon") }}</span>
                            <span>
                                <i
                                    v-if="category.icon?.icon"
                                    :class="`mdi ${category.icon.icon} text-blue-600`"
                                ></i>
                                <i
                                    v-else
                                    class="fas fa-question-circle text-gray-300"
                                ></i>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Empty State for Mobile -->
                <div
                    v-if="categories.length === 0 && !loading"
                    class="bg-white rounded-lg shadow-md p-6 text-center"
                >
                    <div class="flex flex-col items-center text-gray-400">
                        <i class="fas fa-folder-open text-3xl mb-2"></i>
                        <h3 class="text-base font-medium mb-1">
                            {{ __("No categories found") }}
                        </h3>
                        <p class="text-xs">
                            {{
                                __("Create your first category to get started")
                            }}
                        </p>
                    </div>
                </div>

                <!-- Loading State for Mobile -->
                <div
                    v-if="loading"
                    class="bg-white rounded-lg shadow-md p-8 flex items-center justify-center"
                >
                    <div
                        class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"
                    ></div>
                </div>
            </div>

            <!-- Pagination -->
            <v-paginate
                v-model="search.page"
                :total-pages="pages.total_pages"
                @change="getCategories"
            />

            <!-- Results Count -->
            <div class="flex justify-center mt-4">
                <p class="text-xs md:text-sm text-gray-500">
                    {{ __("Showing") }} {{ categories.length }} {{ __("of") }}
                    {{ pages.total || 0 }} {{ __("categories") }}
                </p>
            </div>
        </div>
    </v-admin-layout>
</template>

<script>
import VCreate from "./Create.vue";
import VDelete from "./Delete.vue";
import VAdminLayout from "../../Components/VAdminLayout.vue";
import VPaginate from "@/components/VPaginate.vue";

export default {
    components: {
        VCreate,
        VDelete,
        VAdminLayout,
        VPaginate,
    },

    data() {
        return {
            categories: [],
            loading: false,
            searchTerm: "",
            pages: {
                total_pages: 0,
                total: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            columns: ["Name", "Children", "Status", "Featured", "Actions"],
        };
    },
    
    created() {
        this.getCategories();
    },

    methods: {
        handleSearch() {
            this.search.name = this.searchTerm;
            this.getCategories();
        },

        async getCategories() {
            this.loading = true;
            try {
                const res = await $server.get(this.$page.props.routes.index, {
                    params: this.search,
                });

                if (res.status == 200) {
                    const values = res.data;
                    this.categories = values.data;
                    this.pages = values.meta.pagination;
                }
            } catch (error) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
