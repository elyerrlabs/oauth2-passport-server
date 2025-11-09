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
        <div class="page-header mb-6">
            <div class="header-toolbar flex items-center justify-between mb-2">
                <div class="flex items-center space-x-3">
                    <div
                        class="header-icon w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center"
                    >
                        <i class="mdi mdi-account-group text-white text-lg"></i>
                    </div>
                    <h1
                        class="text-3xl font-bold text-gray-800 dark:text-white"
                    >
                        {{ __("Groups Management") }}
                    </h1>
                </div>
                <div class="flex-1"></div>
                <div class="header-actions flex items-center space-x-3">
                    <v-create @created="getGroups" />
                </div>
            </div>
            <div class="text-gray-600 dark:text-gray-400 text-lg">
                {{ __("Manage user groups and permissions") }}
            </div>
        </div>

        <!-- Filters Section -->
        <div
            class="filters-card bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 mb-6 shadow-sm"
        >
            <div class="p-6">
                <div
                    class="text-xl font-medium text-gray-800 dark:text-white mb-4 flex items-center"
                >
                    <i class="mdi mdi-filter mr-2 text-blue-500"></i>
                    {{ __("Filter Groups") }}
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div class="input-group">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >
                            {{ __("Group Name") }}
                        </label>
                        <input
                            v-model="search.name"
                            @input="getGroups"
                            type="text"
                            placeholder="Search by name..."
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        />
                    </div>

                    <div class="input-group">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >
                            {{ __("Group Type") }}
                        </label>
                        <select
                            v-model="search.system"
                            @change="getGroups"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">{{ __("All Types") }}</option>
                            <option value="1">{{ __("System Groups") }}</option>
                            <option value="0">{{ __("User Groups") }}</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >
                            {{ __("Items per page") }}
                        </label>
                        <select
                            v-model="search.per_page"
                            @change="getGroups"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <button
                            @click="resetFilters"
                            class="w-full bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md transition-colors flex items-center justify-center"
                        >
                            <i class="mdi mdi-refresh mr-2"></i>
                            {{ __("Reset Filters") }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Mode Toggle -->
        <div class="view-toggle flex justify-between items-center mb-4">
            <div class="results-info text-sm text-gray-600 dark:text-gray-400">
                {{ __("Showing") }} {{ groups.length }} {{ __("of") }}
                {{ pages.total_pages }} {{ __("groups") }}
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-1 flex"
            >
                <button
                    @click="viewMode = 'grid'"
                    :class="[
                        'px-3 py-1 rounded-md text-sm font-medium transition-colors',
                        viewMode === 'grid'
                            ? 'bg-blue-500 text-white'
                            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700',
                    ]"
                >
                    <i class="mdi mdi-view-grid mr-1"></i>
                    {{ __("Grid") }}
                </button>
                <button
                    @click="viewMode = 'list'"
                    :class="[
                        'px-3 py-1 rounded-md text-sm font-medium transition-colors',
                        viewMode === 'list'
                            ? 'bg-blue-500 text-white'
                            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700',
                    ]"
                >
                    <i class="mdi mdi-view-list mr-1"></i>
                    {{ __("List") }}
                </button>
            </div>
        </div>

        <!-- Grid & List Views -->
        <div
            v-if="viewMode === 'grid'"
            class="groups-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6"
        >
            <div
                v-for="group in groups"
                :key="group.id"
                class="group-card bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow"
            >
                <div
                    class="card-header p-6 text-center border-b border-gray-200 dark:border-gray-700"
                >
                    <div class="group-icon mb-4">
                        <div
                            class="w-14 h-14 bg-blue-500 rounded-full flex items-center justify-center mx-auto"
                        >
                            <i
                                class="mdi mdi-account-group text-white text-2xl"
                            ></i>
                        </div>
                    </div>
                    <div
                        class="group-title text-xl font-bold text-blue-600 dark:text-blue-400 mb-1"
                    >
                        {{ __(group.name) }}
                    </div>
                    <div
                        class="group-slug text-sm text-gray-500 dark:text-gray-400"
                    >
                        @{{ group.slug }}
                    </div>
                </div>

                <div class="card-content p-6">
                    <div
                        class="group-description text-gray-700 dark:text-gray-300 mb-4 line-clamp-3"
                    >
                        {{ __(group.description) }}
                    </div>

                    <div class="group-meta space-y-2">
                        <div
                            class="group-type-badge inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                            :class="
                                group.system
                                    ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300'
                                    : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300'
                            "
                        >
                            <i
                                :class="
                                    group.system
                                        ? 'mdi mdi-shield-check'
                                        : 'mdi mdi-account'
                                "
                                class="text-sm mr-1"
                            ></i>
                            {{
                                group.system
                                    ? __("System Group")
                                    : __("User Group")
                            }}
                        </div>

                        <div
                            class="text-xs text-gray-500 dark:text-gray-400 flex items-center"
                        >
                            <i class="mdi mdi-calendar mr-1 text-sm"></i>
                            {{ __("Created") }}
                            {{ group.created_at }}
                        </div>
                    </div>
                </div>

                <div
                    class="card-actions p-4 border-t border-gray-200 dark:border-gray-700 flex justify-center space-x-2"
                >
                    <v-create @updated="getGroups" :item="group" />
                    <v-delete
                        v-if="!group.system"
                        @deleted="getGroups"
                        :item="group"
                    />
                </div>
            </div>
        </div>

        <!-- List View -->
        <div v-else class="groups-list mb-6">
            <div
                class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden"
            >
                <div class="overflow-x-auto">
                    <table
                        class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
                    >
                        <thead class="bg-white dark:bg-gray-700">
                            <tr>
                                <th
                                    v-for="(column, index) in columns"
                                    :key="index"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                                >
                                    {{ __(column) }}
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
                            v-if="!loading && groups.length > 0"
                        >
                            <tr
                                v-for="group in groups"
                                :key="group.id"
                                class="hover:bg-white dark:hover:bg-gray-700 transition-colors"
                            >
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
                                >
                                    {{ __(group.name) }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                >
                                    @{{ group.slug }}
                                </td>
                                <td
                                    class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400"
                                >
                                    <div class="line-clamp-2 max-w-xs">
                                        {{ __(group.description) }}
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-center"
                                >
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                        :class="
                                            group.system
                                                ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300'
                                                : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300'
                                        "
                                    >
                                        <i
                                            :class="
                                                group.system
                                                    ? 'mdi mdi-shield-check'
                                                    : 'mdi mdi-account'
                                            "
                                            class="text-sm mr-1"
                                        ></i>
                                        {{
                                            group.system
                                                ? __("System")
                                                : __("User")
                                        }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium"
                                >
                                    <div class="flex justify-center space-x-2">
                                        <v-create
                                            @updated="getGroups"
                                            :item="group"
                                        />
                                        <v-delete
                                            v-if="!group.system"
                                            @deleted="getGroups"
                                            :item="group"
                                        />
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else-if="loading">
                            <tr>
                                <td
                                    :colspan="columns.length"
                                    class="px-6 py-12 text-center"
                                >
                                    <div
                                        class="flex justify-center items-center"
                                    >
                                        <div
                                            class="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"
                                        ></div>
                                        <span
                                            class="ml-2 text-gray-600 dark:text-gray-400"
                                            >{{ __("Loading...") }}</span
                                        >
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td
                                    :colspan="columns.length"
                                    class="px-6 py-12 text-center"
                                >
                                    <div class="empty-state">
                                        <div
                                            class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4"
                                        >
                                            <i
                                                class="mdi mdi-account-group-off text-gray-400 text-2xl"
                                            ></i>
                                        </div>
                                        <div
                                            class="empty-title text-lg font-medium text-gray-700 dark:text-gray-300 mb-2"
                                        >
                                            {{ __("No groups found") }}
                                        </div>
                                        <div
                                            class="empty-subtitle text-gray-500 dark:text-gray-400"
                                        >
                                            {{
                                                __(
                                                    "Try adjusting your search filters"
                                                )
                                            }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <v-paginate
            :total-pages="pages.total_pages"
            v-model="search.page"
            @change="getGroups"
        />
    </v-admin-layout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import VAdminLayout from "@/layouts/VAdminLayout.vue";
import VPaginate from "@/components/VPaginate.vue";
import VCreate from "./Create.vue";
import VDelete from "./Delete.vue";

const page = usePage();

const groups = ref([]);
const viewMode = ref("list");
const loading = ref(false);
const pages = ref({ total_pages: 0 });
const search = useForm({
    page: 1,
    per_page: 15,
    name: "",
    system: "", //true | false | null
});

const columns = ref(["Group Name", "Slug", "Description", "Type", "Actions"]);

onMounted(() => {
    const values = page.props.data;
    groups.value = values.data;
    pages.value = values.meta.pagination;
});

const getGroups = () => {
    loading.value = true;

    search.get(page.props.route, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (page) => {
            const values = page.props.data;
            groups.value = values.data;
            pages.value = values.meta.pagination;
        },
        onError: (e) => {
            if (e?.response?.data?.message) {
                $notify.error(e.response.data.message);
            }
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};
</script>
