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
            <div
                class="flex flex-col sm:flex-row sm:items-center justify-between gap-4"
            >
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold">
                        {{ __("Categories Management") }}
                    </h1>
                    <p class="text-blue-100 dark:text-blue-200 text-sm mt-1">
                        {{ __("Manage product categories and organization") }}
                    </p>
                </div>
                <div class="w-full sm:w-auto">
                    <button
                        @click="router.visit($page.props.routes.create)"
                        class="w-full sm:w-auto inline-flex items-center justify-center py-2 px-4 bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 text-white rounded-lg font-medium transition-colors"
                    >
                        <i class="fas fa-plus mr-2"></i>
                        {{ __("Create Category") }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="p-4 sm:p-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
            <!-- Search -->
            <div class="mb-6">
                <div class="relative">
                    <input
                        v-model="searchTerm"
                        :placeholder="__('Search categories...')"
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                        @input="handleSearch"
                    />
                    <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                    >
                        <i
                            class="fas fa-search text-gray-400 dark:text-gray-500"
                        ></i>
                    </div>
                </div>
            </div>

            <!-- Mobile & Tablet View -->
            <div class="lg:hidden">
                <!-- Grid View -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div
                        v-for="category in categories"
                        :key="category.id"
                        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700"
                    >
                        <div class="p-4">
                            <!-- Header -->
                            <div class="flex items-start gap-3 mb-3">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center"
                                    >
                                        <i
                                            v-if="category.icon?.icon"
                                            :class="`mdi ${category.icon.icon} text-blue-600 dark:text-blue-400`"
                                        ></i>
                                        <i
                                            v-else
                                            class="fas fa-folder text-blue-600 dark:text-blue-400"
                                        ></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3
                                        class="font-semibold text-gray-900 dark:text-white truncate"
                                    >
                                        {{ category.name }}
                                    </h3>
                                    <p
                                        class="text-sm text-gray-500 dark:text-gray-400 truncate"
                                    >
                                        {{ category.slug }}
                                    </p>
                                </div>
                            </div>

                            <!-- Stats -->
                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <div
                                    class="text-center p-2 bg-gray-50 dark:bg-gray-700 rounded"
                                >
                                    <div
                                        class="text-base font-bold text-blue-600 dark:text-blue-400"
                                    >
                                        {{ category.children.length }}
                                    </div>
                                    <div
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        {{ __("Children") }}
                                    </div>
                                </div>
                                <div
                                    class="text-center p-2 bg-gray-50 dark:bg-gray-700 rounded"
                                >
                                    <div
                                        class="text-base font-bold text-green-600 dark:text-green-400"
                                    >
                                        {{ category.products_count || 0 }}
                                    </div>
                                    <div
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        {{ __("Products") }}
                                    </div>
                                </div>
                            </div>

                            <!-- Parent -->
                            <div v-if="category.parent?.id" class="mb-3">
                                <div
                                    class="flex items-center text-sm text-gray-600 dark:text-gray-400"
                                >
                                    <i
                                        class="fas fa-level-up-alt rotate-90 mr-2"
                                    ></i>
                                    <span>{{ __("Parent") }}:</span>
                                    <span
                                        class="ml-1 font-medium text-gray-900 dark:text-white"
                                    >
                                        {{ category.parent.name }}
                                    </span>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="flex flex-wrap gap-2 mb-3">
                                <span
                                    :class="`inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${
                                        category.published
                                            ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300'
                                            : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300'
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
                                            : __("Hidden")
                                    }}
                                </span>
                                <span
                                    :class="`inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${
                                        category.featured
                                            ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300'
                                            : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300'
                                    }`"
                                >
                                    <i class="fas fa-star mr-1"></i>
                                    {{
                                        category.featured
                                            ? __("Featured")
                                            : __("Standard")
                                    }}
                                </span>
                            </div>

                            <!-- Actions -->
                            <div
                                class="flex items-center justify-between pt-3 border-t border-gray-200 dark:border-gray-600"
                            >
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ category.created_at }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <a
                                        :href="category?.links?.edit"
                                        class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white text-sm rounded transition-colors"
                                    >
                                        <i class="fas fa-edit mr-1"></i>
                                        {{ __("Edit") }}
                                    </a>
                                    <v-delete
                                        :item="category"
                                        @deleted="getCategories"
                                        class="action-btn"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div
                    v-if="categories.length === 0 && !loading"
                    class="text-center py-8"
                >
                    <div
                        class="flex flex-col items-center text-gray-400 dark:text-gray-500"
                    >
                        <i class="fas fa-folder-open text-3xl mb-2"></i>
                        <h3
                            class="text-base font-medium text-gray-900 dark:text-white mb-1"
                        >
                            {{ __("No categories found") }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{
                                __("Create your first category to get started")
                            }}
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
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700"
                >
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        v-for="(label, index) in columns"
                                        :key="index"
                                        class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase border-b border-gray-200 dark:border-gray-600"
                                    >
                                        {{ __(label) }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-gray-200 dark:divide-gray-600"
                            >
                                <tr
                                    v-for="category in categories"
                                    :key="category.id"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                >
                                    <!-- Name -->
                                    <td class="px-4 sm:px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center"
                                            >
                                                <i
                                                    v-if="category.icon?.icon"
                                                    :class="`mdi ${category.icon.icon} text-blue-600 dark:text-blue-400`"
                                                ></i>
                                                <i
                                                    v-else
                                                    class="fas fa-folder text-blue-600 dark:text-blue-400"
                                                ></i>
                                            </div>
                                            <div>
                                                <div
                                                    class="font-medium text-gray-900 dark:text-white"
                                                >
                                                    {{ category.name }}
                                                </div>
                                                <div
                                                    class="text-sm text-gray-500 dark:text-gray-400"
                                                >
                                                    {{ category.slug }}
                                                </div>
                                                <div
                                                    v-if="category.parent?.id"
                                                    class="flex items-center mt-1 text-xs text-gray-500 dark:text-gray-400"
                                                >
                                                    <i
                                                        class="fas fa-level-up-alt rotate-90 mr-1"
                                                    ></i>
                                                    {{ category.parent.name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Children -->
                                    <td class="px-4 sm:px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 rounded-full font-medium text-sm"
                                        >
                                            {{ category.children.length }}
                                        </span>
                                    </td>

                                    <!-- Products -->
                                    <td class="px-4 sm:px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center justify-center w-8 h-8 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 rounded-full font-medium text-sm"
                                        >
                                            {{ category.products_count || 0 }}
                                        </span>
                                    </td>

                                    <!-- Published -->
                                    <td class="px-4 sm:px-6 py-4 text-center">
                                        <span
                                            :class="`inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ${
                                                category.published
                                                    ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300'
                                                    : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300'
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
                                                    : __("Hidden")
                                            }}
                                        </span>
                                    </td>

                                    <!-- Featured -->
                                    <td class="px-4 sm:px-6 py-4 text-center">
                                        <span
                                            :class="`inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ${
                                                category.featured
                                                    ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300'
                                                    : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300'
                                            }`"
                                        >
                                            <i class="fas fa-star mr-1"></i>
                                            {{
                                                category.featured
                                                    ? __("Featured")
                                                    : __("Standard")
                                            }}
                                        </span>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-4 sm:px-6 py-4">
                                        <div
                                            class="flex items-center justify-end gap-2"
                                        >
                                            <a
                                                :href="category?.links?.edit"
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white text-sm rounded transition-colors"
                                            >
                                                <i class="fas fa-edit mr-2"></i>
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
                            </tbody>
                        </table>
                    </div>

                    <!-- Empty State -->
                    <div
                        v-if="categories.length === 0 && !loading"
                        class="text-center py-12"
                    >
                        <div
                            class="flex flex-col items-center text-gray-400 dark:text-gray-500"
                        >
                            <i class="fas fa-folder-open text-4xl mb-3"></i>
                            <h3
                                class="text-lg font-medium text-gray-900 dark:text-white mb-1"
                            >
                                {{ __("No categories found") }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{
                                    __(
                                        "Create your first category to get started"
                                    )
                                }}
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
                v-model="search.page"
                :total-pages="pages.total_pages"
                @change="getCategories"
                class="mt-6"
            />

            <!-- Results Count -->
            <div class="flex justify-center mt-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __("Showing") }} {{ categories.length }} {{ __("of") }}
                    {{ pages.total || 0 }} {{ __("categories") }}
                </p>
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

const categories = ref([]);
const loading = ref(false);
const searchTerm = ref("");
const pages = ref({
    total_pages: 0,
});
const search = ref({
    page: 1,
    per_page: 15,
});

const columns = ref([
    "Name",
    "Children",
    "Products",
    "Status",
    "Featured",
    "Actions",
]);

onMounted(async () => {
    await getCategories();
});

const handleSearch = () => {
    search.value.name = searchTerm.value;
    getCategories();
};

const getCategories = async () => {
    loading.value = true;

    try {
        const res = await $server.get(page.props.api.categories, {
            params: search.value,
        });

        if (res.status == 200) {
            const values = res.data;
            categories.value = values.data;
            pages.value = values.meta.pagination;
        }
    } catch (error) {
        if (error?.response?.data?.message) {
            $notify.error(error.response.data.message);
        }
    } finally {
        loading.value = false;
    }
};
</script>
