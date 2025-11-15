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
            <div
                class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6"
            >
                <div>
                    <h1
                        class="text-3xl font-bold text-blue-600 dark:text-blue-400"
                    >
                        {{ __("Services Management") }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        {{
                            __("Manage and organize your application services")
                        }}
                    </p>
                </div>

                <div
                    class="flex flex-col sm:flex-row items-start sm:items-center gap-3"
                >
                    <!-- Create Button -->
                    <v-create @created="getServices" />

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
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4"
            >
                <!-- Name Search Filter -->
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                        {{ __("Search by Name") }}
                    </label>
                    <input
                        type="text"
                        v-model="search.name"
                        @input="getServices"
                        :placeholder="__('Enter service name...')"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                    />
                </div>

                <!-- Group Filter -->
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                        {{ __("Filter by Group") }}
                    </label>
                    <select
                        v-model="search.group"
                        @change="getServices"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                    >
                        <option value="">{{ __("All Groups") }}</option>
                        <option
                            v-for="group in groups"
                            :key="group.slug"
                            :value="group.name"
                        >
                            {{ __(group.name) }}
                        </option>
                    </select>
                </div>

                <!-- Visibility Filter -->
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                        {{ __("Filter by Visibility") }}
                    </label>
                    <select
                        v-model="search.visibility"
                        @change="getServices"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                    >
                        <option value="">{{ __("All Visibility") }}</option>
                        <option value="public">{{ __("Public") }}</option>
                        <option value="private">{{ __("Private") }}</option>
                        <option value="internal">{{ __("Internal") }}</option>
                    </select>
                </div>

                <!-- System Filter -->
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                        {{ __("Service Type") }}
                    </label>
                    <select
                        v-model="search.system"
                        @change="getServices"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                    >
                        <option value="">{{ __("All Services") }}</option>
                        <option value="true">
                            {{ __("System Services") }}
                        </option>
                        <option value="false">
                            {{ __("Custom Services") }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- Quick Stats and Clear Filters -->
            <div
                class="flex flex-col sm:flex-row items-center justify-between gap-4"
            >
                <!-- Quick Stats -->
                <div
                    class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400"
                >
                    <span class="flex items-center gap-1">
                        <i class="mdi mdi-cog"></i>
                        {{ services.length }} {{ __("service")
                        }}{{ services.length !== 1 ? "s" : "" }}
                    </span>
                    <span
                        v-if="systemServicesCount > 0"
                        class="flex items-center gap-1"
                    >
                        <i class="mdi mdi-shield-cog"></i>
                        {{ systemServicesCount }} {{ __("system") }}
                    </span>
                    <span
                        v-if="customServicesCount > 0"
                        class="flex items-center gap-1"
                    >
                        <i class="mdi mdi-cog"></i>
                        {{ customServicesCount }} {{ __("custom") }}
                    </span>
                </div>

                <!-- Clear Filters and Results Per Page -->
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
                            @change="getServices"
                            class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                        >
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>
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
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-md hover:shadow-lg dark:hover:shadow-gray-900 transition-all duration-200"
            >
                <div
                    class="bg-blue-600 dark:bg-blue-700 text-white p-4 rounded-t-lg"
                >
                    <div class="flex justify-between items-start">
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-lg truncate">
                                {{ __(service.name) }}
                            </div>
                            <div
                                class="text-blue-100 dark:text-blue-200 text-sm opacity-90 truncate"
                            >
                                {{ __(service.group.name) }}
                            </div>
                        </div>
                        <v-detail :service="service" />
                    </div>
                </div>

                <div class="p-4">
                    <div
                        class="text-gray-700 dark:text-gray-300 line-clamp-3 mb-3"
                    >
                        {{
                            __(service.description) ||
                            __("No description provided")
                        }}
                    </div>

                    <div class="flex flex-wrap gap-2 mb-3">
                        <span
                            :class="[
                                'inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium transition-colors duration-200',
                                service.system
                                    ? 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300'
                                    : 'bg-orange-100 dark:bg-orange-900/40 text-orange-800 dark:text-orange-300',
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
                            class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 rounded-full text-xs font-medium transition-colors duration-200"
                        >
                            <i class="mdi mdi-eye"></i>
                            {{ __(service.visibility) }}
                        </span>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 p-3">
                    <div class="flex justify-end gap-2">
                        <v-create :item="service" @updated="getServices" />
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
            <i
                class="mdi mdi-cog-off text-6xl text-gray-300 dark:text-gray-600"
            ></i>
            <div class="text-xl text-gray-500 dark:text-gray-400 mt-4">
                {{ __("No services found") }}
            </div>
            <div class="text-gray-400 dark:text-gray-500">
                {{
                    __(
                        "Try adjusting your filters or create your first service to get started"
                    )
                }}
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
                <div class="col-span-4">{{ __("Service") }}</div>
                <div class="col-span-2 text-center">{{ __("System") }}</div>
                <div class="col-span-3 text-center">{{ __("Visibility") }}</div>
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
                    v-for="service in services"
                    :key="service.id"
                    class="grid grid-cols-12 gap-4 p-4 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200"
                >
                    <div class="col-span-4">
                        <div
                            class="font-semibold text-blue-600 dark:text-blue-400"
                        >
                            {{ __(service.name) }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __(service.group.name) }}
                        </div>
                        <div
                            v-if="service.description"
                            class="text-sm text-gray-600 dark:text-gray-500 mt-1 line-clamp-1"
                        >
                            {{ __(service.description) }}
                        </div>
                    </div>

                    <div class="col-span-2 flex justify-center">
                        <span
                            :class="[
                                'inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium transition-colors duration-200',
                                service.system
                                    ? 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300'
                                    : 'bg-orange-100 dark:bg-orange-900/40 text-orange-800 dark:text-orange-300',
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
                            class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 rounded-full text-xs font-medium transition-colors duration-200"
                        >
                            <i class="mdi mdi-eye"></i>
                            {{ __(service.visibility) }}
                        </span>
                    </div>

                    <div class="col-span-3">
                        <div class="flex justify-end gap-2">
                            <v-detail :service="service" />
                            <v-create :item="service" @updated="getServices" />
                            <v-delete
                                v-if="!service?.system"
                                :item="service"
                                @deleted="getServices"
                            />
                        </div>
                    </div>
                </div>

                <!-- Empty State for List View -->
                <div v-if="services.length === 0" class="text-center py-16">
                    <i
                        class="mdi mdi-cog-off text-5xl text-gray-300 dark:text-gray-600"
                    ></i>
                    <div class="text-gray-500 dark:text-gray-400 mt-4 text-lg">
                        {{ __("No services available") }}
                    </div>
                    <div class="text-gray-400 dark:text-gray-500 text-sm mt-2">
                        {{
                            __(
                                "Try adjusting your filters or create a new service"
                            )
                        }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <v-paginate
            v-if="pages.total_pages > 1"
            v-model="search.page"
            :total-pages="pages.total_pages"
            @change="getServices"
        />
    </v-admin-layout>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import VAdminLayout from "@/layouts/VAdminLayout.vue";
import VPaginate from "@/components/VPaginate.vue";
import VCreate from "./Create.vue";
import VDelete from "./Delete.vue";
import VDetail from "./Scope.vue";
import { useForm, usePage } from "@inertiajs/vue3";

const page = usePage();
/**
 * State
 */
const viewMode = ref("list");
const services = ref([]);
const loading = ref(false);
const pages = ref({
    total_pages: 0,
});
const search = useForm({
    page: 1,
    per_page: 15,
    name: "",
    group: "",
    visibility: "",
    system: "",
});
const groups = ref([]);

const viewOptions = [
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

/**
 * Computed
 */
const systemServicesCount = computed(
    () => services.value.filter((s) => s.system).length
);
const customServicesCount = computed(
    () => services.value.filter((s) => !s.system).length
);

onMounted(() => {
    const values = page.props.data;
    services.value = values.data;
    pages.value = values.meta.pagination;

    groups.value = page.props.groups.data;
});

/**
 * Methods
 */

const clearFilters = () => {
    search.reset();
    getServices();
};

const getServices = async () => {
    loading.value = true;

    search.get(page.props.route, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (page) => {
            const values = page.props.data;
            services.value = values.data;
            pages.value = values.meta.pagination;
            loading.value = false;
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
