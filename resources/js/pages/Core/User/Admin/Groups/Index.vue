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
        <div class="page-header mb-4">
            <div class="header-toolbar flex items-center justify-between mb-2">
                <div class="flex items-center space-x-3">
                    <div
                        class="header-icon w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center"
                    >
                        <i class="mdi mdi-account-group text-white text-lg"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        {{ __("Groups Management") }}
                    </h1>
                </div>
                <div class="flex-1"></div>
                <div class="header-actions flex items-center space-x-3">
                    <v-filter
                        :params="params"
                        @change="searching"
                        class="mr-3"
                    />
                    <v-create @created="getGroups" class="mr-3" />
                </div>
            </div>
            <div class="text-gray-600 text-lg">
                {{ __("Manage user groups and permissions") }}
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="stats-overview grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div
                class="stats-card bg-white rounded-lg border border-gray-200 p-4 shadow-sm"
            >
                <div class="flex items-center">
                    <div
                        class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4"
                    >
                        <i
                            class="mdi mdi-account-group text-blue-600 text-xl"
                        ></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-blue-600">
                            {{ groups.length }}
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ __("Total Groups") }}
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="stats-card bg-white rounded-lg border border-gray-200 p-4 shadow-sm"
            >
                <div class="flex items-center">
                    <div
                        class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4"
                    >
                        <i
                            class="mdi mdi-shield-account text-green-600 text-xl"
                        ></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-green-600">
                            {{ systemGroupsCount }}
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ __("System Groups") }}
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="stats-card bg-white rounded-lg border border-gray-200 p-4 shadow-sm"
            >
                <div class="flex items-center">
                    <div
                        class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-4"
                    >
                        <i class="mdi mdi-account text-orange-600 text-xl"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-orange-600">
                            {{ userGroupsCount }}
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ __("User Groups") }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Mode Toggle -->
        <div class="view-toggle flex justify-end mb-4">
            <div class="bg-white rounded-lg border border-gray-200 p-1 flex">
                <button
                    @click="viewMode = 'grid'"
                    :class="[
                        'px-3 py-1 rounded-md text-sm font-medium transition-colors',
                        viewMode === 'grid'
                            ? 'bg-blue-500 text-white'
                            : 'text-gray-600 hover:bg-gray-100',
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
                            : 'text-gray-600 hover:bg-gray-100',
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
                class="group-card bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow"
            >
                <div
                    class="card-header p-6 text-center border-b border-gray-200"
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
                        class="group-title text-xl font-bold text-blue-600 mb-1"
                    >
                        {{ group.name }}
                    </div>
                    <div class="group-slug text-sm text-gray-500">
                        @{{ group.slug }}
                    </div>
                </div>

                <div class="card-content p-6">
                    <div
                        class="group-description text-gray-700 mb-4 line-clamp-3"
                    >
                        {{ group.description || __("No description provided") }}
                    </div>

                    <div class="group-meta space-y-2">
                        <div
                            class="group-type-badge inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                            :class="
                                group.system
                                    ? 'bg-blue-100 text-blue-800'
                                    : 'bg-gray-100 text-gray-800'
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

                        <div class="text-xs text-gray-500 flex items-center">
                            <i class="mdi mdi-calendar mr-1 text-sm"></i>
                            {{ __("Created") }}
                            {{ formatDate(group.created_at) }}
                        </div>
                    </div>
                </div>

                <div
                    class="card-actions p-4 border-t border-gray-200 flex justify-center space-x-2"
                >
                    <v-update
                        @updated="getGroups"
                        :item="group"
                        class="action-btn"
                    />
                    <v-delete
                        v-if="!group.system"
                        @deleted="getGroups"
                        :item="group"
                        class="action-btn"
                    />
                </div>
            </div>
        </div>

        <!-- List View -->
        <div v-else class="groups-list mb-6">
            <div
                class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden"
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
                                    {{ __(column) }}
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="bg-white divide-y divide-gray-200"
                            v-if="!loading && groups.length > 0"
                        >
                            <tr
                                v-for="group in groups"
                                :key="group.id"
                                class="hover:bg-gray-50 transition-colors"
                            >
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                >
                                    {{ group.name }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    @{{ group.slug }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <div class="line-clamp-2 max-w-xs">
                                        {{
                                            group.description ||
                                            __("No description")
                                        }}
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-center"
                                >
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                        :class="
                                            group.system
                                                ? 'bg-blue-100 text-blue-800'
                                                : 'bg-gray-100 text-gray-800'
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
                                        <v-update
                                            @updated="getGroups"
                                            :item="group"
                                            class="action-btn"
                                        />
                                        <v-delete
                                            v-if="!group.system"
                                            @deleted="getGroups"
                                            :item="group"
                                            class="action-btn"
                                        />
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else-if="loading">
                            <tr>
                                <td class="px-6 py-12 text-center">
                                    <div
                                        class="flex justify-center items-center"
                                    >
                                        <div
                                            class="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"
                                        ></div>
                                        <span class="ml-2 text-gray-600">{{
                                            __("Loading...")
                                        }}</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td class="px-6 py-12 text-center">
                                    <div class="empty-state">
                                        <div
                                            class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4"
                                        >
                                            <i
                                                class="mdi mdi-account-group-off text-gray-400 text-2xl"
                                            ></i>
                                        </div>
                                        <div
                                            class="empty-title text-lg font-medium text-gray-700 mb-2"
                                        >
                                            {{ __("No groups found") }}
                                        </div>
                                        <div
                                            class="empty-subtitle text-gray-500"
                                        >
                                            {{
                                                __(
                                                    "Create your first group to get started"
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

<script>
import VAdminLayout from "@/layouts/VAdminLayout.vue";
import VPaginate from "@/components/VPaginate.vue";
import VCreate from "./Create.vue";
import VUpdate from "./Update.vue";
import VDelete from "./Delete.vue";

export default {
    components: {
        VCreate,
        VUpdate,
        VDelete,
        VAdminLayout,
        VPaginate,
    },

    data() {
        return {
            groups: [],
            viewMode: "list",
            loading: false,
            params: [{ key: "Name", value: "name" }],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            columns: ["Group Name", "Slug", "Description", "Type", "Actions"],
        };
    },

    computed: {
        systemGroupsCount() {
            return this.groups.filter((group) => group.system).length;
        },
        userGroupsCount() {
            return this.groups.filter((group) => !group.system).length;
        },
    },

    created() {
        this.getGroups();
    },

    methods: {
        changePage(event) {
            this.search.page = event;
        },

        searching(event) {
            this.getGroups(event);
        },

        getGroups(param = null) {
            this.loading = true;
            var params = { ...this.search, ...param };

            this.$server
                .get(this.$page.props.route, {
                    params: params,
                })
                .then((res) => {
                    this.groups = res.data.data;
                    let meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                })
                .catch((e) => {
                    if (e?.response?.data?.message) {
                        $notify.error(e.response.data.message);
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        formatDate(dateString) {
            if (!dateString) return "N/A";
            return new Date(dateString).toLocaleDateString("en-US", {
                year: "numeric",
                month: "short",
                day: "numeric",
            });
        },
    },
};
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Transiciones suaves */
.group-card {
    transition: all 0.3s ease;
}

.stats-card {
    transition: all 0.2s ease;
}

.stats-card:hover {
    transform: translateY(-2px);
}

/* Asegurar que los iconos de Material Design se vean bien */
.mdi {
    font-family: "Material Design Icons";
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .groups-grid {
        grid-template-columns: 1fr;
    }

    .header-toolbar {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .header-actions {
        width: 100%;
        justify-content: flex-end;
    }
}
</style>
