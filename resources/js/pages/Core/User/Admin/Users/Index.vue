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
            class="bg-white dark:bg-gray-800 p-6 shadow-lg rounded-lg transition-colors duration-200 mb-4"
        >
            <div class="md:flex items-center justify-between mb-6">
                <div>
                    <div
                        class="text-md md:text-lg lg:text-3xl font-bold text-blue-600 dark:text-blue-400"
                    >
                        {{ __("User Management") }}
                    </div>
                    <div class="text-gray-600 dark:text-gray-400 mt-1">
                        {{ __("Manage system users and their permissions") }}
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <!-- Create Button -->
                    <v-create @created="getUsers" />

                    <!-- View Toggle -->
                    <div
                        class="flex bg-gray-100 dark:bg-gray-700 rounded-lg p-1 transition-colors duration-200"
                    >
                        <button
                            v-for="option in viewOptions"
                            :key="option.value"
                            @click="viewMode = option.value"
                            :class="[
                                'px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200',
                                viewMode === option.value
                                    ? 'bg-blue-600 dark:bg-blue-500 text-white shadow'
                                    : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200',
                            ]"
                        >
                            <span class="flex items-center space-x-1">
                                <i :class="option.icon" class="text-sm"></i>
                                <span>{{ option.label }}</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Enhanced Filter Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
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
                        @input="debouncedSearch"
                        :placeholder="__('Enter name...')"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                    />
                </div>

                <!-- Last Name Filter -->
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                        {{ __("Search by Last Name") }}
                    </label>
                    <input
                        type="text"
                        v-model="search.last_name"
                        @input="debouncedSearch"
                        :placeholder="__('Enter last name...')"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                    />
                </div>

                <!-- Email Filter -->
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                        {{ __("Search by Email") }}
                    </label>
                    <input
                        type="text"
                        v-model="search.email"
                        @input="debouncedSearch"
                        :placeholder="__('Enter email...')"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                    />
                </div>
            </div>

            <!-- Quick Stats and Controls -->
            <div
                class="flex flex-col sm:flex-row items-center justify-between gap-4"
            >
                <!-- Quick Stats -->
                <div
                    class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400"
                >
                    <span class="flex items-center gap-1">
                        <i
                            class="mdi mdi-account-group text-gray-500 dark:text-gray-400"
                        ></i>
                        {{ users.length }} {{ __("user")
                        }}{{ users.length !== 1 ? "s" : "" }}
                    </span>
                    <span
                        v-if="activeUsersCount > 0"
                        class="flex items-center gap-1"
                    >
                        <i
                            class="mdi mdi-check-circle text-green-500 dark:text-green-400"
                        ></i>
                        {{ activeUsersCount }} {{ __("active") }}
                    </span>
                    <span
                        v-if="inactiveUsersCount > 0"
                        class="flex items-center gap-1"
                    >
                        <i
                            class="mdi mdi-close-circle text-orange-500 dark:text-orange-400"
                        ></i>
                        {{ inactiveUsersCount }} {{ __("inactive") }}
                    </span>
                </div>

                <!-- Controls -->
                <div class="flex items-center gap-3">
                    <!-- Clear Filters -->
                    <button
                        @click="clearFilters"
                        class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center gap-2"
                    >
                        <i class="mdi mdi-filter-remove"></i>
                        {{ __("Clear Filters") }}
                    </button>

                    <!-- Results Per Page -->
                    <div class="flex items-center gap-2">
                        <label
                            class="text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap"
                        >
                            {{ __("Per page:") }}
                        </label>
                        <select
                            v-model="search.per_page"
                            @change="changePage"
                            class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                        >
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                            <option value="500">500</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid View -->
        <div
            v-if="viewMode === 'grid' && users.length > 0"
            class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6"
        >
            <div
                v-for="user in users"
                :key="user.id"
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-md hover:shadow-lg dark:hover:shadow-gray-900 transition-all duration-200"
            >
                <div
                    class="bg-blue-600 dark:bg-blue-700 text-white p-4 rounded-t-lg"
                >
                    <div class="text-lg font-bold truncate">
                        {{ user.name }} {{ user.last_name }}
                    </div>
                    <div
                        class="text-sm opacity-90 mt-1 truncate text-blue-100 dark:text-blue-200"
                    >
                        {{ user.email }}
                    </div>
                </div>

                <div class="p-4">
                    <div class="flex flex-wrap gap-2 mb-3">
                        <span
                            :class="[
                                'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium transition-colors duration-200',
                                user.disabled
                                    ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-800 dark:text-orange-300'
                                    : 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300',
                            ]"
                        >
                            <i
                                :class="[
                                    'mdi mr-1 text-sm',
                                    user.disabled
                                        ? 'mdi-close-circle text-orange-600 dark:text-orange-400'
                                        : 'mdi-check-circle text-green-600 dark:text-green-400',
                                ]"
                            ></i>
                            {{ user.disabled ? __("Inactive") : __("Active") }}
                        </span>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 p-3">
                    <div class="flex justify-center gap-2">
                        <v-create
                            v-if="!user.disabled"
                            @updated="getUsers"
                            :item="user"
                        />
                        <v-scopes v-if="!user.disabled" :item="user" />
                        <v-revoke v-if="!user.disabled" :item="user" />
                        <v-status :item="user" @updated="getUsers" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State for Grid View -->
        <div
            v-else-if="viewMode === 'grid' && users.length === 0"
            class="text-center p-12"
        >
            <i
                class="mdi mdi-account-off-outline text-6xl text-gray-300 dark:text-gray-600"
            ></i>
            <div class="text-xl text-gray-500 dark:text-gray-400 mt-4">
                {{ __("No users found") }}
            </div>
            <div class="text-gray-400 dark:text-gray-500 mt-2">
                {{
                    __(
                        "Try adjusting your filters or create your first user to get started"
                    )
                }}
            </div>
        </div>

        <!-- List View -->
        <div v-else class="mb-6">
            <!-- Mobile/Tablet Cards (sm:1, md:2) -->
            <div class="lg:hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                <div
                    v-for="user in users"
                    :key="user.id"
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm"
                >
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1 min-w-0">
                            <h3
                                class="font-semibold text-gray-900 dark:text-white truncate"
                            >
                                {{ user.name }} {{ user.last_name }}
                            </h3>
                            <p
                                class="text-sm text-gray-500 dark:text-gray-400 truncate"
                            >
                                {{ user.email }}
                            </p>
                        </div>
                        <span
                            :class="[
                                'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                user.disabled
                                    ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-800 dark:text-orange-300'
                                    : 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300',
                            ]"
                        >
                            <i
                                :class="[
                                    'mdi mr-1 text-xs',
                                    user.disabled
                                        ? 'mdi-close-circle text-orange-600 dark:text-orange-400'
                                        : 'mdi-check-circle text-green-600 dark:text-green-400',
                                ]"
                            ></i>
                            {{ user.disabled ? __("Inactive") : __("Active") }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ __("User") }}
                        </div>
                        <div class="flex space-x-1">
                            <v-create
                                v-if="!user.disabled"
                                @updated="getUsers"
                                :item="user"
                            />
                            <v-scopes v-if="!user.disabled" :item="user" />
                            <v-revoke v-if="!user.disabled" :item="user" />
                            <v-status :item="user" @updated="getUsers" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Table (lg+) -->
            <div class="hidden lg:block">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden transition-colors duration-200"
                >
                    <div class="overflow-x-auto">
                        <table
                            class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
                        >
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        v-for="(column, index) in columns"
                                        :key="index"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                                    >
                                        {{ column }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
                            >
                                <tr
                                    v-for="user in users"
                                    :key="user.id"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200"
                                >
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div
                                            class="font-bold text-blue-600 dark:text-blue-400"
                                        >
                                            {{ user.name }} {{ user.last_name }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div
                                            class="text-gray-900 dark:text-gray-100"
                                        >
                                            {{ user.email }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            :class="[
                                                'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium transition-colors duration-200',
                                                user.disabled
                                                    ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-800 dark:text-orange-300'
                                                    : 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300',
                                            ]"
                                        >
                                            <i
                                                :class="[
                                                    'mdi mr-1 text-sm',
                                                    user.disabled
                                                        ? 'mdi-close-circle text-orange-600 dark:text-orange-400'
                                                        : 'mdi-check-circle text-green-600 dark:text-green-400',
                                                ]"
                                            ></i>
                                            {{
                                                user.disabled
                                                    ? __("Inactive")
                                                    : __("Active")
                                            }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex justify-end space-x-2">
                                            <v-create
                                                v-if="!user.disabled"
                                                :item="user"
                                                @updated="getUsers"
                                            />
                                            <v-scopes
                                                v-if="!user.disabled"
                                                :item="user"
                                            />
                                            <v-revoke
                                                v-if="!user.disabled"
                                                :item="user"
                                            />
                                            <v-status
                                                :item="user"
                                                @updated="getUsers"
                                            />
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Empty State for Table -->
                    <div v-if="users.length === 0" class="text-center py-12">
                        <i
                            class="mdi mdi-account-off-outline text-5xl text-gray-300 dark:text-gray-600"
                        ></i>
                        <div class="text-gray-500 dark:text-gray-400 mt-4">
                            {{ __("No users available") }}
                        </div>
                        <div
                            class="text-gray-400 dark:text-gray-500 text-sm mt-2"
                        >
                            {{
                                __(
                                    "Try adjusting your filters or create a new user"
                                )
                            }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <v-paginate
            v-if="pages.total_pages > 1"
            v-model="search.page"
            :total-pages="pages.total_pages"
            @change="getUsers"
        />
    </v-admin-layout>
</template>

<script setup>
import VAdminLayout from "@/components/VGeneralLayout.vue";
import VPaginate from "@/components/VPaginate.vue";
import VCreate from "./Create.vue";
import VScopes from "./Scopes.vue";
import VStatus from "./Status.vue";
import VRevoke from "./Revoke.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { ref, reactive, onMounted, computed } from "vue";

const page = usePage();

const viewMode = ref("list");
const loading = ref(false);
const columns = reactive([
    __("Name"),
    __("Email"),
    __("Status"),
    __("Actions"),
]);
const users = ref([]);
const pages = ref({
    total_pages: 0,
});

const search = useForm({
    page: 1,
    per_page: 15,
    name: "",
    last_name: "",
    email: "",
});

// Computed properties
const activeUsersCount = computed(() => {
    return users.value.filter((user) => !user.disabled).length;
});

const inactiveUsersCount = computed(() => {
    return users.value.filter((user) => user.disabled).length;
});

const viewOptions = computed(() => {
    return [
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
    ];
});

// Search timeout for debouncing
const searchTimeout = ref(null);

// Methods

const changePage = () => {
    search.page = 1;
    getUsers();
};

const debouncedSearch = () => {
    clearTimeout(searchTimeout.value);
    searchTimeout.value = setTimeout(() => {
        search.page = 1;
        getUsers();
    }, 500);
};

const clearFilters = () => {
    search.reset();
    getUsers();
};

const getUsers = () => {
    loading.value = true;

    search.get(page.props.route, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (page) => {
            const values = page.props.data;
            users.value = values.data;
            pages.value = values.meta.pagination;
            loading.value = false;
        },
        onError: () => {
            loading.value = false;
        },
    });
};

onMounted(() => {
    const values = page.props.data;
    users.value = values.data;
    pages.value = values.meta.pagination;
});
</script>
