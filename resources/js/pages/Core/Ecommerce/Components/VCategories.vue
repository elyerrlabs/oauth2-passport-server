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
    <div class="py-2 max-h-screen overflow-y-auto">
        <!-- Loader -->
        <div v-if="loading" class="flex justify-center items-center py-4">
            <div class="flex space-x-2">
                <div
                    class="w-2 h-2 bg-blue-500 rounded-full animate-bounce"
                ></div>
                <div
                    class="w-2 h-2 bg-blue-500 rounded-full animate-bounce"
                    style="animation-delay: 0.1s"
                ></div>
                <div
                    class="w-2 h-2 bg-blue-500 rounded-full animate-bounce"
                    style="animation-delay: 0.2s"
                ></div>
            </div>
        </div>

        <!-- Categories List -->
        <ul v-else class="space-y-1">
            <li v-for="category in categories" :key="category.id" class="group">
                <!-- Main Category Item -->
                <div
                    class="flex items-center px-3 py-2 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all duration-200 cursor-pointer border border-transparent hover:border-blue-200 dark:hover:border-blue-800"
                    @click="goTo(category.links?.index)"
                >
                    <!-- Category Icon/Image -->
                    <div
                        class="flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg mr-3 flex-shrink-0"
                    >
                        <img
                            v-if="category.images?.[0]?.url"
                            :src="category.images[0].url"
                            :alt="category.name"
                            class="w-6 h-6 object-cover rounded"
                        />
                        <i
                            v-else
                            class="fas fa-folder text-blue-600 dark:text-blue-400 text-sm"
                        ></i>
                    </div>

                    <!-- Category Name -->
                    <span
                        class="flex-1 text-sm font-medium text-gray-700 dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors truncate"
                    >
                        {{ category.name }}
                    </span>

                    <!-- Children Count & Arrow -->
                    <div class="flex items-center space-x-1 shrink-0">
                        <span
                            v-if="hasChildren(category)"
                            class="text-xs text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded"
                        >
                            {{ category.children.length }}
                        </span>
                        <i
                            v-if="hasChildren(category)"
                            :class="[
                                'fas text-xs text-gray-400 transition-transform duration-200 cursor-pointer',
                                expandedCategories.includes(category.id)
                                    ? 'fa-chevron-down'
                                    : 'fa-chevron-right',
                            ]"
                            @click.stop="toggleCategory(category.id)"
                        ></i>
                    </div>
                </div>

                <!-- Subcategories -->
                <transition
                    enter-active-class="transition-all duration-200 ease-out"
                    enter-from-class="opacity-0 max-h-0"
                    enter-to-class="opacity-100 max-h-96"
                    leave-active-class="transition-all duration-150 ease-in"
                    leave-from-class="opacity-100 max-h-96"
                    leave-to-class="opacity-0 max-h-0"
                >
                    <div
                        v-if="
                            expandedCategories.includes(category.id) &&
                            hasChildren(category)
                        "
                        class="ml-4 pl-4 border-l-2 border-blue-200 dark:border-blue-700 mt-1 mb-2"
                    >
                        <div class="space-y-1">
                            <div
                                v-for="subcategory in category.children"
                                :key="subcategory.id"
                                class="flex items-center py-1.5 px-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors group/sub"
                                @click="goTo(subcategory.web?.index)"
                            >
                                <!-- Subcategory Icon -->
                                <div
                                    class="w-6 h-6 flex items-center justify-center mr-2 shrink-0"
                                >
                                    <img
                                        v-if="subcategory.images?.[0]?.url"
                                        :src="subcategory.images[0].url"
                                        :alt="subcategory.name"
                                        class="w-4 h-4 object-cover rounded"
                                    />
                                    <i
                                        v-else
                                        class="fas fa-folder text-gray-400 dark:text-gray-500 text-xs"
                                    ></i>
                                </div>

                                <!-- Subcategory Name -->
                                <span
                                    class="text-xs text-gray-600 dark:text-gray-300 group-hover/sub:text-blue-600 dark:group-hover/sub:text-blue-400 transition-colors truncate flex-1"
                                >
                                    {{ subcategory.name }}
                                </span>

                                <!-- Hot Badge for Featured Subcategories -->
                                <span
                                    v-if="subcategory.featured"
                                    class="ml-2 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full flex-shrink-0"
                                >
                                    Hot
                                </span>
                            </div>
                        </div>
                    </div>
                </transition>
            </li>
        </ul>

        <!-- Empty State -->
        <div
            v-if="!loading && categories.length === 0"
            class="text-center py-8"
        >
            <div
                class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3"
            >
                <i
                    class="fas fa-folder-open text-gray-400 dark:text-gray-500"
                ></i>
            </div>
            <p class="text-gray-500 dark:text-gray-400 text-sm">
                {{ __("No categories found") }}
            </p>
        </div>
    </div>
</template>

<script>
export default {
    name: "CategoryMenu",

    data() {
        return {
            categories: [],
            loading: true,
            expandedCategories: [],
        };
    },

    created() {
        this.getCategories();
    },

    methods: {
        async getCategories() {
            this.loading = true;
            try {
                const res = await this.$server.get(
                    "/api/ecommerce/categories",
                    {
                        params: {
                            parent: "",
                            per_page: 50,
                        },
                    }
                );

                if (res.status === 200) {
                    this.categories = res.data.data;

                    // Auto-expand first category with children
                    const firstCategoryWithChildren = this.categories.find(
                        (cat) => this.hasChildren(cat)
                    );
                    if (firstCategoryWithChildren) {
                        this.expandedCategories.push(
                            firstCategoryWithChildren.id
                        );
                    }
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },

        hasChildren(category) {
            return (
                Array.isArray(category.children) && category.children.length > 0
            );
        },

        toggleCategory(categoryId) {
            const index = this.expandedCategories.indexOf(categoryId);
            if (index > -1) {
                this.expandedCategories.splice(index, 1);
            } else {
                this.expandedCategories.push(categoryId);
            }
        },

        goTo(url) {
            if (url) {
                window.location.href = url;
            }
        },
    },
};
</script>

<style scoped>
/* Custom scrollbar for the menu */
.max-h-screen {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 transparent;
}

.max-h-screen::-webkit-scrollbar {
    width: 4px;
}

.max-h-screen::-webkit-scrollbar-track {
    background: transparent;
}

.max-h-screen::-webkit-scrollbar-thumb {
    background-color: #cbd5e0;
    border-radius: 2px;
}

.max-h-screen::-webkit-scrollbar-thumb:hover {
    background-color: #a0aec0;
}

/* Dark mode scrollbar */
.dark .max-h-screen {
    scrollbar-color: #4a5568 transparent;
}

.dark .max-h-screen::-webkit-scrollbar-thumb {
    background-color: #4a5568;
}

.dark .max-h-screen::-webkit-scrollbar-thumb:hover {
    background-color: #718096;
}

/* Ensure text doesn't overflow */
.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>
