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
        <div
            class="bg-white dark:bg-gray-800 p-6 shadow-lg rounded-xl mb-6 transition-colors duration-200"
        >
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1
                        class="text-3xl font-bold text-blue-600 dark:text-blue-400"
                    >
                        {{ __("Roles Management") }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        {{ __("Manage user roles and permissions") }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Create Button -->
                    <v-create @created="getRoles" />

                    <!-- View Toggle -->
                    <div
                        class="flex bg-gray-100 dark:bg-gray-700 rounded-lg p-1 transition-colors duration-200"
                    >
                        <button
                            v-for="option in viewOptions"
                            :key="option.value"
                            @click="viewMode = option.value"
                            :class="[
                                'flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200',
                                viewMode === option.value
                                    ? 'bg-white dark:bg-gray-600 text-blue-600 dark:text-blue-400 shadow-sm'
                                    : 'text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200',
                            ]"
                        >
                            <i :class="option.icon"></i>
                            <span>{{ option.label }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Enhanced Filter Section -->
            <div
                class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-4"
            >
                <!-- Name Filter -->
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                        {{ __("Search by Name") }}
                    </label>
                    <input
                        type="text"
                        v-model="search.name"
                        @input="getRoles"
                        :placeholder="__('Enter role name...')"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                    />
                </div>

                <!-- System Role Filter -->
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                        {{ __("Role Type") }}
                    </label>
                    <select
                        v-model="search.system"
                        @change="getRoles"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                    >
                        <option value="">{{ __("All Roles") }}</option>
                        <option value="true">
                            {{ __("System Roles Only") }}
                        </option>
                        <option value="false">
                            {{ __("Custom Roles Only") }}
                        </option>
                    </select>
                </div>

                <!-- Results Per Page -->
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                        {{ __("Results per page") }}
                    </label>
                    <select
                        v-model="search.per_page"
                        @change="getRoles"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                    >
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>

                <!-- Clear Filters -->
                <div class="flex items-end">
                    <button
                        @click="clearFilters"
                        class="w-full px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center justify-center gap-2"
                    >
                        <i class="mdi mdi-filter-remove"></i>
                        {{ __("Clear Filters") }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Grid View -->
        <div
            v-if="viewMode === 'grid' && roles.length > 0"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
        >
            <div
                v-for="role in roles"
                :key="role.id"
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-md hover:shadow-lg dark:hover:shadow-gray-900 transition-all duration-200"
            >
                <div
                    class="bg-blue-600 dark:bg-blue-700 text-white p-4 rounded-t-lg"
                >
                    <div class="font-bold text-lg truncate">
                        {{ __(role.name) }}
                    </div>
                    <div
                        class="text-blue-100 dark:text-blue-200 text-sm opacity-90"
                    >
                        {{ role.slug }}
                    </div>
                </div>

                <div class="p-4">
                    <div
                        class="text-gray-700 dark:text-gray-300 line-clamp-3 mb-3"
                    >
                        {{ role.description || __("No description provided") }}
                    </div>

                    <span
                        :class="[
                            'inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium transition-colors duration-200',
                            role.system
                                ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-800 dark:text-orange-300'
                                : 'bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300',
                        ]"
                    >
                        <i
                            :class="
                                role.system
                                    ? 'mdi mdi-shield-check'
                                    : 'mdi mdi-account-cog'
                            "
                        ></i>
                        {{
                            role.system ? __("System Role") : __("Custom Role")
                        }}
                    </span>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 p-3">
                    <div class="flex justify-end gap-2">
                        <v-create @updated="getRoles" :item="role" />
                        <v-delete
                            v-if="!role.system"
                            @deleted="getRoles"
                            :item="role"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State for Grid View -->
        <div
            v-else-if="viewMode === 'grid' && roles.length === 0"
            class="text-center py-16"
        >
            <i
                class="mdi mdi-account-off-outline text-6xl text-gray-300 dark:text-gray-600"
            ></i>
            <div class="text-xl text-gray-500 dark:text-gray-400 mt-4">
                {{ __("No roles found") }}
            </div>
            <div class="text-gray-400 dark:text-gray-500">
                {{ __("Create your first role to get started") }}
            </div>
        </div>

        <!-- List View -->
        <div
            v-else
            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm overflow-hidden transition-colors duration-200"
        >
            <!-- Table Header -->
            <div
                class="grid grid-cols-12 gap-4 p-4 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 font-semibold text-gray-700 dark:text-gray-300 transition-colors duration-200"
            >
                <div class="col-span-5">{{ __("Role") }}</div>
                <div class="col-span-4 text-center">
                    {{ __("Type") }}
                </div>
                <div class="col-span-3 text-right">{{ __("Actions") }}</div>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="p-8 text-center">
                <div
                    class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 dark:border-blue-400"
                ></div>
                <p class="text-gray-500 dark:text-gray-400 mt-2">
                    {{ __("Loading...") }}
                </p>
            </div>

            <!-- Table Rows -->
            <div v-else>
                <div
                    v-for="role in roles"
                    :key="role.id"
                    class="grid grid-cols-12 gap-4 p-4 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200"
                >
                    <div class="col-span-5">
                        <div
                            class="font-semibold text-gray-900 dark:text-white"
                        >
                            {{ __(role.name) }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ role.slug }}
                        </div>
                        <div
                            v-if="role.description"
                            class="text-sm text-gray-600 dark:text-gray-500 mt-1 line-clamp-1"
                        >
                            {{ role.description }}
                        </div>
                    </div>

                    <div class="col-span-4 flex justify-center">
                        <span
                            :class="[
                                'inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium transition-colors duration-200',
                                role.system
                                    ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-800 dark:text-orange-300'
                                    : 'bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300',
                            ]"
                        >
                            <i
                                :class="
                                    role.system
                                        ? 'mdi mdi-shield-check'
                                        : 'mdi mdi-account-cog'
                                "
                            ></i>
                            {{ role.system ? __("System") : __("Custom") }}
                        </span>
                    </div>

                    <div class="col-span-3">
                        <div class="flex justify-end gap-2">
                            <v-create @updated="getRoles" :item="role" />
                            <v-delete
                                v-if="!role.system"
                                @deleted="getRoles"
                                :item="role"
                            />
                        </div>
                    </div>
                </div>

                <!-- Empty State for List View -->
                <div v-if="roles.length === 0" class="text-center py-16">
                    <i
                        class="mdi mdi-account-off-outline text-5xl text-gray-300 dark:text-gray-600"
                    ></i>
                    <div class="text-gray-500 dark:text-gray-400 mt-4 text-lg">
                        {{ __("No roles available") }}
                    </div>
                    <div class="text-gray-400 dark:text-gray-500 text-sm mt-2">
                        {{
                            __(
                                "Try adjusting your filters or create a new role"
                            )
                        }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <v-paginate
            v-model="search.page"
            :total-pages="pages.total_pages"
            @change="getRoles"
        />
    </v-admin-layout>
</template>

<script setup>
import VAdminLayout from "@/layouts/VAdminLayout.vue";
import VPaginate from "@/components/VPaginate.vue";
import VCreate from "./Create.vue";
import VDelete from "./Delete.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { ref, reactive, onMounted } from "vue";

const page = usePage();

const roles = ref([]);
const loading = ref(false);
const viewMode = ref("list");
const pages = ref({
    total_pages: 0,
});

const search = useForm({
    page: 1,
    per_page: 15,
    name: "",
    system: "",
});
const viewOptions = reactive([
    {
        value: "list",
        icon: "mdi mdi-format-list-bulleted",
        label: __("List"),
    },
    {
        value: "grid",
        icon: "mdi mdi-view-grid-outline",
        label: __("Grid"),
    },
]);

// mounted
onMounted(() => {
    const values = page.props.data;
    roles.value = values.data;
    pages.value = values.meta.pagination;
});

// methods
const clearFilters = () => {
    search.reset();
    getRoles();
};

const getRoles = () => {
    search.get(page.props.route, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (page) => {
            const values = page.props.data;
            roles.value = values.data;
            pages.value = values.meta.pagination;
        },
    });
};
</script>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
