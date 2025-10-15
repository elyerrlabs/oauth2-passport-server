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
            <div
                class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6"
            >
                <div>
                    <h1 class="text-3xl font-bold text-blue-600">
                        {{ __("Services Management") }}
                    </h1>
                    <p class="text-gray-600 mt-1">
                        {{
                            __("Manage and organize your application services")
                        }}
                    </p>
                </div>

                <div
                    class="flex flex-col sm:flex-row items-start sm:items-center gap-3"
                >
                    <!-- Group Filter -->
                    <div class="relative min-w-[200px]">
                        <v-select
                            v-model="group"
                            :options="groups"
                            value-key="slug"
                            @change="filterByGroup"
                        />
                    </div>

                    <!-- Create Button -->
                    <v-create @created="getServices" />

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
            v-if="services.length > 0"
        >
            <div
                class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center"
            >
                <div class="text-xl font-semibold text-blue-700 mb-2">
                    {{ services.length }} {{ __("Service")
                    }}{{ services.length !== 1 ? "s" : "" }}
                </div>
                <i class="mdi mdi-cog text-2xl text-blue-600"></i>
            </div>

            <div
                class="bg-green-50 border border-green-200 rounded-lg p-4 text-center"
            >
                <div class="text-xl font-semibold text-green-700 mb-2">
                    {{ systemServicesCount }} {{ __("System Service")
                    }}{{ systemServicesCount !== 1 ? "s" : "" }}
                </div>
                <i class="mdi mdi-shield-cog text-2xl text-green-600"></i>
            </div>
        </div>

        <!-- Grid View -->
        <div
            v-if="viewMode === 'grid' && services.length > 0"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-3"
        >
            <div
                v-for="service in services"
                :key="service.id"
                class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow"
            >
                <div class="bg-blue-600 text-white p-4 rounded-t-lg">
                    <div class="flex justify-between items-start">
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-lg truncate">
                                {{ __(service.name) }}
                            </div>
                            <div
                                class="text-blue-100 text-sm opacity-90 truncate"
                            >
                                {{ __(service.group.name) }}
                            </div>
                        </div>
                        <v-detail :service="service" />
                    </div>
                </div>

                <div class="p-4">
                    <div class="text-gray-700 line-clamp-3 mb-3">
                        {{
                            __(service.description) ||
                            __("No description provided")
                        }}
                    </div>

                    <div class="flex flex-wrap gap-2 mb-3">
                        <span
                            :class="[
                                'inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium',
                                service.system
                                    ? 'bg-green-100 text-green-800'
                                    : 'bg-orange-100 text-orange-800',
                            ]"
                        >
                            <i
                                :class="
                                    service.system
                                        ? 'mdi mdi-shield-check'
                                        : 'mdi mdi-cog'
                                "
                            ></i>
                            {{ service.system ? __("System") : __("Custom") }}
                        </span>

                        <span
                            class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium"
                        >
                            <i class="mdi mdi-eye"></i>
                            {{ __(service.visibility) }}
                        </span>
                    </div>
                </div>

                <div class="border-t border-gray-200 p-3">
                    <div class="flex justify-end gap-2">
                        <v-update :item="service" @updated="getServices" />
                        <v-delete
                            v-if="!service.system"
                            :item="service"
                            @deleted="getServices"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State for Grid View -->
        <div
            v-else-if="viewMode === 'grid' && services.length === 0"
            class="text-center py-16"
        >
            <i class="mdi mdi-cog-off text-6xl text-gray-300"></i>
            <div class="text-xl text-gray-500 mt-4">
                {{ __("No services found") }}
            </div>
            <div class="text-gray-400" v-if="group">
                {{ __("Try changing your group filter or") }}
            </div>
            <div class="text-gray-400">
                {{ __("create your first service to get started") }}
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
                <div class="col-span-4">{{ __("Service") }}</div>
                <div class="col-span-2 text-center">{{ __("System") }}</div>
                <div class="col-span-3 text-center">{{ __("Visibility") }}</div>
                <div class="col-span-3 text-right">{{ __("Actions") }}</div>
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
                    v-for="service in services"
                    :key="service.id"
                    class="grid grid-cols-12 gap-4 p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors"
                >
                    <div class="col-span-4">
                        <div class="font-semibold text-blue-600">
                            {{ __(service.name) }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ __(service.group.name) }}
                        </div>
                    </div>

                    <div class="col-span-2 flex justify-center">
                        <span
                            :class="[
                                'inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium',
                                service.system
                                    ? 'bg-green-100 text-green-800'
                                    : 'bg-orange-100 text-orange-800',
                            ]"
                        >
                            <i
                                :class="
                                    service.system
                                        ? 'mdi mdi-shield-check'
                                        : 'mdi mdi-cog'
                                "
                            ></i>
                            {{ service.system ? __("Yes") : __("No") }}
                        </span>
                    </div>

                    <div class="col-span-3 flex justify-center">
                        <span
                            class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium"
                        >
                            <i class="mdi mdi-eye"></i>
                            {{ __(service.visibility) }}
                        </span>
                    </div>

                    <div class="col-span-3">
                        <div class="flex justify-end gap-2">
                            <v-detail :service="service" />
                            <v-update :item="service" @updated="getServices" />
                            <v-delete
                                v-if="!service.system"
                                :item="service"
                                @deleted="getServices"
                            />
                        </div>
                    </div>
                </div>

                <!-- Empty State for List View -->
                <div v-if="services.length === 0" class="text-center py-16">
                    <i class="mdi mdi-cog-off text-5xl text-gray-300"></i>
                    <div class="text-gray-500 mt-4 text-lg">
                        {{ __("No services available") }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div
            class="flex flex-col sm:flex-row items-center justify-between gap-4 my-8"
            v-if="pages.total_pages > 1"
        >
            <!-- Items per page -->
            <div class="flex items-center gap-3">
                <label class="text-sm text-gray-600">{{
                    __("Items per page")
                }}</label>
                <div class="relative">
                    <select
                        v-model="search.per_page"
                        @change="getServices"
                        class="pl-10 pr-8 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none bg-white"
                    >
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <i
                        class="mdi mdi-format-list-numbered absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"
                    ></i>
                    <i
                        class="mdi mdi-chevron-down absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"
                    ></i>
                </div>
            </div>

            <!-- Pagination Numbers -->
            <v-paginate
                v-model="search.page"
                :total-pages="pages.total_pages"
                @change="getServices"
            />
        </div>
    </v-admin-layout>
</template>

<script>
import VAdminLayout from "@/layouts/VAdminLayout.vue";
import VPaginate from "@/components/VPaginate.vue";
import VCreate from "./Create.vue";
import VUpdate from "./Update.vue";
import VDelete from "./Delete.vue";
import VDetail from "./Scope.vue";

export default {
    components: {
        VCreate,
        VUpdate,
        VDelete,
        VDetail,
        VAdminLayout,
        VPaginate,
    },

    data() {
        return {
            viewMode: "list",
            services: [],
            loading: false,
            params: [
                { key: "Name", value: "name" },
                { key: "Group", value: "group" },
                { key: "Visibility", value: "visibility" },
                { key: "Created", value: "created" },
                { key: "Updated", value: "updated" },
            ],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            groups: [],
            group: null,
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
        systemServicesCount() {
            return this.services.filter((service) => service.system).length;
        },
    },

    created() {
        this.getServices();
        this.getGroups();
    },

    methods: {
        filterByGroup() {
            this.search.group = this.group?.name || null;
            this.getServices();
        },

        changePage(event) {
            this.search.page = event;
        },

        searching(event) {
            this.getServices(event);
        },

        async getGroups() {
            try {
                const res = await this.$server.get(
                    this.$page.props.route["groups"]
                );

                if (res.status == 200) {
                    this.groups = res.data.data;
                }
            } catch (err) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            }
        },

        getServices(param = null) {
            this.loading = true;
            var params = {
                ...this.search,
                ...param,
            };

            this.$server
                .get(this.$page.props.route.services, {
                    params: params,
                })
                .then((res) => {
                    this.services = res.data.data;
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

.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>
