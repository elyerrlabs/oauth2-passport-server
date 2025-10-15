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
        <div class="bg-white p-6 shadow-lg rounded-xl mb-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-blue-600">
                        {{ __("Roles Management") }}
                    </h1>
                    <p class="text-gray-600 mt-1">
                        {{ __("Manage user roles and permissions") }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Create Button -->
                    <v-create @created="getRoles" />

                    <!-- View Toggle -->
                    <div class="flex bg-gray-100 rounded-lg p-1">
                        <button
                            v-for="option in viewOptions"
                            :key="option.value"
                            @click="viewMode = option.value"
                            :class="[
                                'flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition-colors',
                                viewMode === option.value
                                    ? 'bg-white text-blue-600 shadow-sm'
                                    : 'text-gray-600 hover:text-gray-800',
                            ]"
                        >
                            <i :class="option.icon"></i>
                            <span>{{ option.label }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filter Component -->
            <v-filter :params="params" @change="searching" class="mb-4" />
        </div>

        <!-- Stats Overview -->
        <div
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 mt-4"
        >
            <div
                v-if="roles.length > 0"
                class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center"
            >
                <div class="text-xl font-semibold text-blue-700 mb-2">
                    {{ roles.length }} {{ __("Role")
                    }}{{ roles.length !== 1 ? __("s") : "" }}
                </div>
                <i class="mdi mdi-account-group text-2xl text-blue-600"></i>
            </div>

            <div
                v-if="systemRolesCount > 0"
                class="bg-orange-50 border border-orange-200 rounded-lg p-4 text-center"
            >
                <div class="text-xl font-semibold text-orange-700 mb-2">
                    {{ systemRolesCount }} {{ __("System Role")
                    }}{{ systemRolesCount !== 1 ? "s" : "" }}
                </div>
                <i class="mdi mdi-shield-account text-2xl text-orange-600"></i>
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
                class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow"
            >
                <div class="bg-blue-600 text-white p-4 rounded-t-lg">
                    <div class="font-bold text-lg truncate">
                        {{ __(role.name) }}
                    </div>
                    <div class="text-blue-100 text-sm opacity-90">
                        {{ role.slug }}
                    </div>
                </div>

                <div class="p-4">
                    <div class="text-gray-700 line-clamp-3 mb-3">
                        {{ role.description || __("No description provided") }}
                    </div>

                    <span
                        :class="[
                            'inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium',
                            role.system
                                ? 'bg-orange-100 text-orange-800'
                                : 'bg-blue-100 text-blue-800',
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

                <div class="border-t border-gray-200 p-3">
                    <div class="flex justify-end gap-2">
                        <v-update @updated="getRoles" :item="role" />
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
            <i class="mdi mdi-account-off-outline text-6xl text-gray-300"></i>
            <div class="text-xl text-gray-500 mt-4">
                {{ __("No roles found") }}
            </div>
            <div class="text-gray-400">
                {{ __("Create your first role to get started") }}
            </div>
        </div>

        <!-- List View -->
        <div
            v-else
            class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden"
        >
            <!-- Table Header -->
            <div
                class="grid grid-cols-12 gap-4 p-4 bg-gray-50 border-b border-gray-200 font-semibold text-gray-700"
            >
                <div class="col-span-5">{{ __("Role") }}</div>
                <div class="col-span-5 text-center">
                    {{ __("System Role") }}
                </div>
                <div class="col-span-2 text-right">{{ __("Actions") }}</div>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="p-8 text-center">
                <div
                    class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"
                ></div>
                <p class="text-gray-500 mt-2">{{ __("Loading...") }}</p>
            </div>

            <!-- Table Rows -->
            <div v-else>
                <div
                    v-for="role in roles"
                    :key="role.id"
                    class="grid grid-cols-12 gap-4 p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors"
                >
                    <div class="col-span-5">
                        <div class="font-semibold text-gray-900">
                            {{ __(role.name) }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ __(role.slug) }}
                        </div>
                    </div>

                    <div class="col-span-5 flex justify-center">
                        <span
                            :class="[
                                'inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium',
                                role.system
                                    ? 'bg-orange-100 text-orange-800'
                                    : 'bg-blue-100 text-blue-800',
                            ]"
                        >
                            <i
                                :class="
                                    role.system
                                        ? 'mdi mdi-shield-check'
                                        : 'mdi mdi-account-cog'
                                "
                            ></i>
                            {{ role.system ? __("Yes") : __("No") }}
                        </span>
                    </div>

                    <div class="col-span-2">
                        <div class="flex justify-end gap-2">
                            <v-update @updated="getRoles" :item="role" />
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
                        class="mdi mdi-account-off-outline text-5xl text-gray-300"
                    ></i>
                    <div class="text-gray-500 mt-4 text-lg">
                        {{ __("No roles available") }}
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
            roles: [],
            loading: false,
            viewMode: "list",
            params: [{ key: "Name", value: "name" }],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            viewOptions: [
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
            ],
        };
    },

    computed: {
        systemRolesCount() {
            return this.roles.filter((role) => role.system).length;
        },
    },

    created() {
        this.getRoles();
    },

    methods: {
        searching(event) {
            this.getRoles(event);
        },

        getRoles(param = null) {
            this.loading = true;
            var params = { ...this.search, ...param };

            this.$server
                .get(this.$page.props.route, {
                    params: params,
                })
                .then((res) => {
                    this.roles = res.data.data;
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

        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);

                $notify.success(__("Copied to clipboard"));
            } catch (err) {
                $notify.error(__("Failed to copy"));
            }
        },
    },
};
</script>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
