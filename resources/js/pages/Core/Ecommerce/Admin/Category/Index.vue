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
                                    v-for="col in columns"
                                    :key="col.name"
                                    class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    {{ __(col.label) }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="group in groups"
                                :key="group.id"
                                class="hover:bg-gray-50 transition-colors duration-150"
                            >
                                <!-- Name Column -->
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i
                                            v-if="group.icon?.icon"
                                            :class="`mdi ${group.icon.icon} text-blue-600 mr-2 text-lg`"
                                        ></i>
                                        <span class="font-medium text-gray-900">
                                            {{ group.name }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Slug Column -->
                                <td
                                    class="px-4 md:px-6 py-4 whitespace-nowrap text-center"
                                >
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"
                                    >
                                        {{ group.slug }}
                                    </span>
                                </td>

                                <!-- Icon Column -->
                                <td
                                    class="px-4 md:px-6 py-4 whitespace-nowrap text-center"
                                >
                                    <i
                                        v-if="group.icon?.icon"
                                        :class="`mdi ${group.icon.icon} text-blue-600 text-xl`"
                                    ></i>
                                    <i
                                        v-else
                                        class="fas fa-question-circle text-gray-300 text-xl"
                                    ></i>
                                </td>

                                <!-- Published Column -->
                                <td
                                    class="px-4 md:px-6 py-4 whitespace-nowrap text-center"
                                >
                                    <span
                                        :class="`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                            group.published
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-gray-100 text-gray-800'
                                        }`"
                                    >
                                        <i
                                            :class="`fas ${
                                                group.published
                                                    ? 'fa-check-circle'
                                                    : 'fa-times-circle'
                                            } mr-1`"
                                        ></i>
                                        {{
                                            group.published
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
                                            group.featured
                                                ? 'bg-yellow-100 text-yellow-800'
                                                : 'bg-gray-100 text-gray-800'
                                        }`"
                                    >
                                        <i
                                            :class="`fas ${
                                                group.featured
                                                    ? 'fa-star'
                                                    : 'fa-star-o'
                                            } mr-1`"
                                        ></i>
                                        {{
                                            group.featured
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
                                            :href="group?.links.edit"
                                            class="py-2 px-4 cursor-pointer bg-green-500 text-gray-100 rounded-full font-medium"
                                        >
                                            <span class="mdi mdi-plus"></span>
                                            {{ __("Edit") }}
                                        </a>
                                        <v-delete
                                            :item="group"
                                            @deleted="getCategories"
                                            class="action-btn"
                                        />
                                    </div>
                                </td>
                            </tr>

                            <!-- Empty State -->
                            <tr v-if="groups.length === 0 && !loading">
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
                    v-for="group in groups"
                    :key="group.id"
                    class="bg-white rounded-lg shadow-md p-4"
                >
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <i
                                v-if="group.icon?.icon"
                                :class="`mdi ${group.icon.icon} text-blue-600 mr-2 text-lg`"
                            ></i>
                            <span class="font-medium text-gray-900">
                                {{ group.name }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a
                                :href="group?.links.edit"
                                class="py-2 px-4 cursor-pointer bg-green-500 text-gray-100 rounded-full font-medium"
                            >
                                <span class="mdi mdi-plus"></span>
                                {{ __("Edit") }}
                            </a>
                            <v-delete
                                :item="group"
                                @deleted="getCategories"
                                class="action-btn"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="flex flex-col">
                            <span class="text-gray-500">{{ __("Slug") }}</span>
                            <span class="font-medium">{{ group.slug }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-gray-500">
                                {{ __("Status") }}
                            </span>
                            <span
                                :class="`inline-flex items-center ${
                                    group.published
                                        ? 'text-green-600'
                                        : 'text-gray-600'
                                }`"
                            >
                                <i
                                    :class="`fas ${
                                        group.published
                                            ? 'fa-check-circle'
                                            : 'fa-times-circle'
                                    } mr-1`"
                                ></i>
                                {{
                                    group.published ? __("Published") : "Hidden"
                                }}
                            </span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-gray-500">
                                {{ __("Featured") }}
                            </span>
                            <span
                                :class="`inline-flex items-center ${
                                    group.featured
                                        ? 'text-yellow-600'
                                        : 'text-gray-600'
                                }`"
                            >
                                <i
                                    :class="`fas ${
                                        group.featured ? 'fa-star' : 'fa-star-o'
                                    } mr-1`"
                                ></i>
                                {{ group.featured ? "Yes" : "No" }}
                            </span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-gray-500">{{ __("Icon") }}</span>
                            <span>
                                <i
                                    v-if="group.icon?.icon"
                                    :class="`mdi ${group.icon.icon} text-blue-600`"
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
                    v-if="groups.length === 0 && !loading"
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
                    {{ __("Showing") }} {{ groups.length }} {{ __("of") }}
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
            groups: [],
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
            columns: [
                {
                    name: "name",
                    label: "Category Name",
                    field: "name",
                    align: "left",
                    sortable: true,
                },
                {
                    name: "slug",
                    label: "Slug",
                    field: "slug",
                    align: "center",
                    sortable: true,
                },
                {
                    name: "icon",
                    label: "Icon",
                    field: (row) => row.icon?.icon,
                    align: "center",
                },
                {
                    name: "published",
                    label: "Status",
                    field: (row) => (row.published ? "Yes" : "No"),
                    align: "center",
                    sortable: true,
                },
                {
                    name: "featured",
                    label: "Featured",
                    field: (row) => (row.featured ? "Yes" : "No"),
                    align: "center",
                    sortable: true,
                },
                {
                    name: "actions",
                    label: "Actions",
                    align: "center",
                    sortable: false,
                },
            ],
        };
    },

    computed: {
        visiblePages() {
            const current = this.search.page;
            const total = this.pages.total_pages;
            const range = 2; // Show 2 pages before and after current

            let start = Math.max(1, current - range);
            let end = Math.min(total, current + range);

            // Adjust if we're near the start or end
            if (current - range <= 1) {
                end = Math.min(total, 1 + range * 2);
            }
            if (current + range >= total) {
                start = Math.max(1, total - range * 2);
            }

            const pages = [];
            for (let i = start; i <= end; i++) {
                pages.push(i);
            }
            return pages;
        },
    },

    created() {
        this.getCategories();
    },

    watch: {
        "search.page"(value) {
            this.getCategories();
        },
        "search.per_page"(value) {
            this.getCategories();
        },
    },

    methods: {
        handleSearch() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.getCategories({ search: this.searchTerm });
            }, 300);
        },

        getCategories(param = null) {
            this.loading = true;
            const params = { ...this.search, ...param };

            this.$server
                .get(this.$page.props.routes.index, {
                    params: params,
                })
                .then((res) => {
                    this.groups = res.data.data;
                    let meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.page = meta.pagination.current_page;
                })
                .catch((e) => {
                    if (e?.response?.data?.message) {
                         $notify(e.response.data.message);
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        },
    },
};
</script>
