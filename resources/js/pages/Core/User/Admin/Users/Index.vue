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
        <div class="bg-white p-6 shadow-lg rounded-lg">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <div class="text-3xl font-bold text-blue-600">
                        {{ __("User Management") }}
                    </div>
                    <div class="text-gray-600 mt-1">
                        {{ __("Manage system users and their permissions") }}
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <!-- Create Button -->
                    <v-create @created="getUsers" />

                    <!-- View Toggle -->
                    <div class="flex bg-gray-100 rounded-lg p-1">
                        <button
                            v-for="option in viewOptions"
                            :key="option.value"
                            @click="viewMode = option.value"
                            :class="[
                                'px-3 py-2 rounded-md text-sm font-medium transition-colors',
                                viewMode === option.value
                                    ? 'bg-blue-600 text-white shadow'
                                    : 'text-gray-600 hover:text-gray-900',
                            ]"
                        >
                            <span class="flex items-center space-x-1">
                                <span>{{ option.label }}</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filter Component -->
            <v-filter :params="params" @change="searching" class="mb-2" />
        </div>

        <!-- Stats Overview -->
        <div
            v-if="users.length > 0"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 mt-4"
        >
            <div
                class="bg-blue-50 text-blue-700 rounded-lg border border-blue-200"
            >
                <div class="p-4 text-center">
                    <div class="text-xl font-semibold">
                        {{ users.length }} {{ __("User")
                        }}{{ users.length !== 1 ? "s" : "" }}
                    </div>
                    <div class="mt-2">
                        <svg
                            class="w-8 h-8 mx-auto"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-green-50 text-green-700 rounded-lg border border-green-200"
            >
                <div class="p-4 text-center">
                    <div class="text-xl font-semibold">
                        {{ activeUsersCount }} {{ __("Active") }}
                    </div>
                    <div class="mt-2">
                        <svg
                            class="w-8 h-8 mx-auto"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-orange-50 text-orange-700 rounded-lg border border-orange-200"
            >
                <div class="p-4 text-center">
                    <div class="text-xl font-semibold">
                        {{ inactiveUsersCount }} {{ __("Inactive") }}
                    </div>
                    <div class="mt-2">
                        <svg
                            class="w-8 h-8 mx-auto"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid View -->
        <div
            v-if="viewMode === 'grid' && users.length > 0"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 p-2"
        >
            <div
                v-for="user in users"
                :key="user.id"
                class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow"
            >
                <div class="bg-blue-600 text-white p-4 rounded-t-lg">
                    <div class="text-lg font-bold truncate">
                        {{ user.name }} {{ user.last_name }}
                    </div>
                    <div class="text-sm opacity-90 mt-1 truncate">
                        {{ user.email }}
                    </div>
                </div>

                <div class="p-4">
                    <div class="flex flex-wrap gap-2 mb-3">
                        <span
                            :class="[
                                'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
                                user.disabled
                                    ? 'bg-orange-100 text-orange-800'
                                    : 'bg-green-100 text-green-800',
                            ]"
                        >
                            <svg
                                class="w-4 h-4 mr-1"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    :d="
                                        user.disabled
                                            ? 'M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z'
                                            : 'M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z'
                                    "
                                />
                            </svg>
                            {{ user.disabled ? __("Inactive") : __("Active") }}
                        </span>
                    </div>
                </div>

                <div class="border-t border-gray-200"></div>

                <div class="p-3 flex justify-between items-center">
                    <v-update
                        v-if="!user.disabled"
                        @updated="getUsers"
                        :item="user"
                    />
                    <v-scopes v-if="!user.disabled" :item="user" class="mx-1" />
                    <v-revoke v-if="!user.disabled" :item="user" class="mx-1" />
                    <v-status :item="user" @updated="getUsers" class="mx-1" />
                </div>
            </div>
        </div>

        <!-- Empty State for Grid View -->
        <div
            v-else-if="viewMode === 'grid' && users.length === 0"
            class="text-center p-12"
        >
            <svg
                class="w-16 h-16 mx-auto text-gray-300"
                fill="currentColor"
                viewBox="0 0 20 20"
            >
                <path
                    fill-rule="evenodd"
                    d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                    clip-rule="evenodd"
                />
            </svg>
            <div class="text-xl text-gray-500 mt-4">
                {{ __("No users found") }}
            </div>
            <div class="text-gray-400 mt-2">
                {{ __("Create your first user to get started") }}
            </div>
        </div>

        <!-- List View -->
        <div
            v-else
            class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden"
        >
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                v-for="(column, index) in columns"
                                :key="index"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                {{ column }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                            v-for="user in users"
                            :key="user.id"
                            class="hover:bg-gray-50 transition-colors"
                        >
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-blue-600">
                                    {{ user.name }} {{ user.last_name }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-gray-900">
                                    {{ user.email }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="[
                                        'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
                                        user.disabled
                                            ? 'bg-orange-100 text-orange-800'
                                            : 'bg-green-100 text-green-800',
                                    ]"
                                >
                                    <svg
                                        class="w-4 h-4 mr-1"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            :d="
                                                user.disabled
                                                    ? 'M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z'
                                                    : 'M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z'
                                            "
                                        />
                                    </svg>
                                    {{
                                        user.disabled
                                            ? __("Inactive")
                                            : __("Active")
                                    }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap w-64">
                                <div class="flex justify-end space-x-2">
                                    <v-update
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
                <svg
                    class="w-16 h-16 mx-auto text-gray-300"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clip-rule="evenodd"
                    />
                </svg>
                <div class="text-gray-500 mt-4">
                    {{ __("No users available") }}
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <v-paginate
            v-model="search.page"
            :total-pages="pages.total_pages"
            @change="getUsers"
        />
    </v-admin-layout>
</template>

<script>
import VAdminLayout from "@/layouts/VAdminLayout.vue";
import VPaginate from "@/components/VPaginate.vue";
import VFilter from "@/components/VFilter.vue";
import VCreate from "./Create.vue";
import VUpdate from "./Update.vue";
import VScopes from "./Scopes.vue";
import VStatus from "./Status.vue";
import VRevoke from "./Revoke.vue";

export default {
    components: {
        VCreate,
        VUpdate,
        VScopes,
        VStatus,
        VRevoke,
        VPaginate,
        VAdminLayout,
        VFilter,
    },

    data() {
        return {
            viewMode: "list",
            loading: false,
            columns: ["Name", "Email", "Status", "Actions"],
            params: [
                { key: "Name", value: "name" },
                { key: "Last Name", value: "last_name" },
                { key: "Email", value: "email" },
                { key: "Created", value: "created" },
                { key: "Updated", value: "updated" },
            ],
            users: [],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
        };
    },

    computed: {
        activeUsersCount() {
            return this.users.filter((user) => !user.disabled).length;
        },
        inactiveUsersCount() {
            return this.users.filter((user) => user.disabled).length;
        },
        viewOptions() {
            return [
                {
                    value: "list",
                    icon: "mdi-format-list-bulleted",
                    label: __("List"),
                },
                {
                    value: "grid",
                    icon: "mdi-view-grid-outline",
                    label: __("Grid"),
                },
            ];
        },
    },

    created() {
        this.getUsers();
    },

    methods: {
        searching(event) {
            this.getUsers(event);
        },

        async getUsers(param = null) {
            this.loading = true;
            var params = { ...this.search, ...param };

            try {
                const res = await this.$server.get(this.$page.props.route, {
                    params: params,
                });

                if (res.status == 200) {
                    this.users = res.data.data;
                    let meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    // You might want to replace this with your notification system
                    console.error(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
