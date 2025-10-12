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

SPDX-->
<template>
    <v-partner-layout>
        <!-- Header Section -->
        <div class="bg-blue-600 text-white px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-lg sm:text-xl font-bold">
                        {{ __("Partner Management") }}
                    </h1>
                    <p class="text-blue-100 text-xs sm:text-sm mt-1">
                        {{ __("Manage your partners and their commissions") }}
                    </p>
                </div>
                <div class="hidden sm:block p-3 bg-blue-500 rounded-lg">
                    <svg
                        class="w-6 h-6 text-white"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"
                        />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white border-b border-gray-200 px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">
                    {{ __("Filters") }}
                </h2>
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <label
                        class="flex items-center space-x-2 text-xs sm:text-sm text-gray-700"
                    >
                        <input
                            type="checkbox"
                            v-model="showFilters"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4"
                        />
                        <span class="hidden sm:inline">{{
                            __("Show Filters")
                        }}</span>
                        <span class="sm:hidden">{{ __("Filters") }}</span>
                    </label>
                    <button
                        @click="resetFilters"
                        class="p-2 text-gray-600 hover:text-blue-600 hover:bg-gray-100 rounded-lg transition-colors"
                        :title="__('Reset filters')"
                    >
                        <svg
                            class="w-4 h-4 sm:w-5 sm:h-5"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"
                            />
                        </svg>
                    </button>
                </div>
            </div>

            <transition
                enter-active-class="transition-all duration-300 ease-out"
                leave-active-class="transition-all duration-200 ease-in"
                enter-from-class="opacity-0 max-h-0"
                enter-to-class="opacity-100 max-h-96"
                leave-from-class="opacity-100 max-h-96"
                leave-to-class="opacity-0 max-h-0"
            >
                <div v-show="showFilters" class="overflow-hidden">
                    <div
                        class="grid grid-cols-1 xs:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4"
                    >
                        <div>
                            <label
                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-1"
                            >
                                {{ __("Name") }}
                            </label>
                            <input
                                v-model="search.name"
                                type="text"
                                @input="debounceGetPartners"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Search by name..."
                            />
                        </div>
                        <div>
                            <label
                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-1"
                            >
                                {{ __("Last Name") }}
                            </label>
                            <input
                                v-model="search.last_name"
                                type="text"
                                @input="debounceGetPartners"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Search by last name..."
                            />
                        </div>
                        <div>
                            <label
                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-1"
                            >
                                {{ __("Email") }}
                            </label>
                            <input
                                v-model="search.email"
                                type="email"
                                @input="debounceGetPartners"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Search by email..."
                            />
                        </div>
                        <div>
                            <label
                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-1"
                            >
                                {{ __("Code") }}
                            </label>
                            <input
                                v-model="search.code"
                                type="text"
                                @input="debounceGetPartners"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Search by code..."
                            />
                        </div>
                    </div>
                </div>
            </transition>
        </div>

        <!-- Info Banner and Controls -->
        <div class="px-4 sm:px-6 py-3 bg-gray-50 border-b border-gray-200">
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
            >
                <div
                    class="flex items-center space-x-2 bg-blue-50 text-blue-700 px-3 py-2 rounded-lg"
                >
                    <svg
                        class="w-4 h-4 sm:w-5 sm:h-5"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"
                        />
                    </svg>
                    <span class="text-xs sm:text-sm font-medium">
                        {{ __("Showing") }} {{ users.length }} {{ __("of") }}
                        {{ pages.total_count || 0 }} {{ __("partners") }}
                    </span>
                </div>

                <div class="flex items-center space-x-2 sm:space-x-4">
                    <select
                        v-model="search.per_page"
                        @change="getPartners"
                        class="px-2 py-1 sm:px-3 sm:py-2 border border-gray-300 rounded-lg text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="10">10 {{ __("items") }}</option>
                        <option value="15">15 {{ __("items") }}</option>
                        <option value="25">25 {{ __("items") }}</option>
                        <option value="50">50 {{ __("items") }}</option>
                        <option value="100">100 {{ __("items") }}</option>
                    </select>

                    <div class="flex bg-gray-100 rounded-lg p-1">
                        <button
                            @click="viewMode = 'list'"
                            :class="[
                                'px-2 py-1 text-xs sm:px-3 sm:py-1 sm:text-sm rounded-md transition-colors',
                                viewMode === 'list'
                                    ? 'bg-blue-600 text-white shadow-sm'
                                    : 'text-gray-600 hover:text-gray-800',
                            ]"
                        >
                            <span class="hidden sm:inline">{{
                                __("List")
                            }}</span>
                            <span class="sm:hidden">📋</span>
                        </button>
                        <button
                            @click="viewMode = 'grid'"
                            :class="[
                                'px-2 py-1 text-xs sm:px-3 sm:py-1 sm:text-sm rounded-md transition-colors',
                                viewMode === 'grid'
                                    ? 'bg-blue-600 text-white shadow-sm'
                                    : 'text-gray-600 hover:text-gray-800',
                            ]"
                        >
                            <span class="hidden sm:inline">{{
                                __("Grid")
                            }}</span>
                            <span class="sm:hidden">🟰</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Grid View (Default for small screens) -->
        <div
            v-if="
                (isMobile && users.length) ||
                (viewMode === 'grid' && users.length)
            "
            class="p-3 sm:p-6"
        >
            <div
                class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6"
            >
                <div
                    v-for="user in users"
                    :key="user.id"
                    class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow overflow-hidden"
                >
                    <!-- Card Header -->
                    <div
                        class="bg-blue-600 text-white px-3 sm:px-4 py-2 sm:py-3"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <h3
                                    class="text-xs sm:text-sm font-bold truncate"
                                >
                                    {{ user.name }} {{ user.last_name }}
                                </h3>
                                <p
                                    class="text-blue-100 text-xs truncate mt-0.5"
                                >
                                    {{ user.email }}
                                </p>
                            </div>
                            <div class="flex-shrink-0 ml-2 sm:ml-3">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 bg-white text-blue-600 rounded-full flex items-center justify-center font-semibold text-xs sm:text-sm"
                                >
                                    {{ userInitials(user) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-3 sm:p-4 space-y-2">
                        <div
                            class="flex items-center text-xs sm:text-sm text-gray-600"
                        >
                            <svg
                                class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 text-gray-400 flex-shrink-0"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"
                                />
                            </svg>
                            <span class="truncate"
                                >{{ __("ID") }}: {{ user.id }}</span
                            >
                        </div>
                        <div
                            class="flex items-center text-xs sm:text-sm text-gray-600"
                        >
                            <svg
                                class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 text-gray-400 flex-shrink-0"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"
                                />
                            </svg>
                            <span class="truncate"
                                >{{ __("Code") }}: {{ user.code }}</span
                            >
                        </div>
                        <div
                            v-if="user.country"
                            class="flex items-center text-xs sm:text-sm text-gray-600"
                        >
                            <svg
                                class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 text-gray-400 flex-shrink-0"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"
                                />
                            </svg>
                            <span class="truncate"
                                >{{ __("Country") }}: {{ user.country }}</span
                            >
                        </div>
                        <div
                            class="flex items-center text-xs sm:text-sm text-gray-600"
                        >
                            <svg
                                class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 text-gray-400 flex-shrink-0"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91 2.56.62 4.18 1.63 4.18 3.71 0 1.76-1.39 2.83-3.13 3.16z"
                                />
                            </svg>
                            <span class="truncate"
                                >{{ __("Commission") }}:
                                {{ user.commission_rate }}%</span
                            >
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div
                        class="px-3 sm:px-4 py-2 sm:py-3 bg-gray-50 border-t border-gray-200"
                    >
                        <div class="flex justify-end">
                            <v-update
                                v-if="!user.disabled"
                                @updated="getPartners"
                                :item="user"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid Empty State -->
        <div
            v-else-if="
                (isMobile && !users.length) ||
                (viewMode === 'grid' && !users.length)
            "
            class="p-6 sm:p-12 text-center"
        >
            <div
                class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-6 sm:p-8"
            >
                <svg
                    class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400 mx-auto mb-3 sm:mb-4"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"
                    />
                </svg>
                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">
                    {{ __("No partners found") }}
                </h3>
                <p class="text-gray-500 text-xs sm:text-sm mb-4">
                    {{ __("No partners found matching your criteria") }}
                </p>
                <button
                    @click="resetFilters"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors"
                >
                    {{ __("Clear Filters") }}
                </button>
            </div>
        </div>

        <!-- Desktop List View (Only for desktop when explicitly selected) -->
        <div v-if="!isMobile && viewMode === 'list'" class="p-3 sm:p-6">
            <div
                class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden"
            >
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[600px]">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    {{ __("Partner") }}
                                </th>
                                <th
                                    class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    {{ __("Code") }}
                                </th>
                                <th
                                    class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    {{ __("Commission") }}
                                </th>
                                <th
                                    class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    {{ __("Actions") }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-if="loading">
                                <td
                                    :colspan="4"
                                    class="px-4 sm:px-6 py-8 text-center"
                                >
                                    <div
                                        class="flex justify-center items-center"
                                    >
                                        <svg
                                            class="w-5 h-5 text-blue-600 animate-spin mr-2"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <circle
                                                class="opacity-25"
                                                cx="12"
                                                cy="12"
                                                r="10"
                                                stroke="currentColor"
                                                stroke-width="4"
                                            ></circle>
                                            <path
                                                class="opacity-75"
                                                fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                            ></path>
                                        </svg>
                                        <span class="text-gray-600">{{
                                            __("Loading...")
                                        }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr v-else-if="users.length === 0">
                                <td
                                    :colspan="4"
                                    class="px-4 sm:px-6 py-8 text-center"
                                >
                                    <div
                                        class="flex flex-col items-center text-gray-500"
                                    >
                                        <svg
                                            class="w-10 h-10 sm:w-12 sm:h-12 text-gray-300 mb-2"
                                            fill="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"
                                            />
                                        </svg>
                                        <span class="mb-2">{{
                                            __("No partners found")
                                        }}</span>
                                        <button
                                            @click="resetFilters"
                                            class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                        >
                                            {{ __("Clear Filters") }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr
                                v-else
                                v-for="user in users"
                                :key="user.id"
                                class="hover:bg-gray-50"
                            >
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-semibold text-sm mr-3"
                                        >
                                            {{ userInitials(user) }}
                                        </div>
                                        <div>
                                            <div
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ user.name }}
                                                {{ user.last_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ user.email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    {{ user.code }}
                                </td>
                                <td
                                    class="px-4 sm:px-6 py-4 whitespace-nowrap text-center"
                                >
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200"
                                    >
                                        {{ user.commission_rate }}%
                                    </span>
                                </td>
                                <td
                                    class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                >
                                    <v-update
                                        :item="user"
                                        @updated="getPartners"
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <v-paginate
            v-if="users.length"
            :total-pages="pages.total_count"
            v-model="search.page"
            @change="getPartners"
        />
    </v-partner-layout>
</template>

<script>
import VUpdate from "./Update.vue";
import VPartnerLayout from "@/layouts/VPartnerLayout.vue";
import VPaginate from "@/components/VPaginate.vue";

export default {
    components: { VUpdate, VPartnerLayout },

    data() {
        return {
            viewMode: "list",
            showFilters: true,
            loading: false,
            users: [],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
                name: "",
                last_name: "",
                email: "",
                code: "",
            },
            debounceTimer: null,
            isMobile: false,
        };
    },

    created() {
        this.checkMobile();
        window.addEventListener("resize", this.checkMobile);
        this.getPartners();
    },

    beforeUnmount() {
        window.removeEventListener("resize", this.checkMobile);
    },

    methods: {
        checkMobile() {
            this.isMobile = window.innerWidth < 768;
            // Auto-switch to grid view on mobile
            if (this.isMobile && this.viewMode === "list") {
                this.viewMode = "grid";
            }
        },

        async getPartners() {
            this.loading = true;

            try {
                const res = await this.$server.get(
                    this.$page.props.routes.partners,
                    { params: this.search }
                );

                if (res.status === 200 && res.data.data.length) {
                    this.users = res.data.data;
                    const meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                } else {
                    this.users = [];
                    this.pages = {
                        total_pages: 0,
                        total_count: 0,
                    };
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },

        debounceGetPartners() {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this.getPartners();
            }, 500);
        },

        resetFilters() {
            this.search = {
                page: 1,
                per_page: 15,
                name: "",
                last_name: "",
                email: "",
                code: "",
            };
            this.getPartners();
        },

        userInitials(user) {
            return (
                `${user.name?.charAt(0) || ""}${
                    user.last_name?.charAt(0) || ""
                }`.toUpperCase() || "U"
            );
        },
    },
};
</script>

<style scoped>
/* Custom breakpoint for extra small screens */
@media (min-width: 475px) {
    .xs\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}
</style>
