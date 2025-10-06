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
    <v-account-layout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50/30">
            <!-- Enhanced Header Section - More Elegant -->
            <div class="px-4 sm:px-6 py-8 lg:px-8">
                <div class="max-w-7xl mx-auto">
                    <!-- Compact User Info -->
                    <div class="flex items-center space-x-4 mb-8">
                        <div class="relative">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg"
                            >
                                <span
                                    class="text-white font-semibold text-lg"
                                    >{{ userInitials }}</span
                                >
                            </div>
                            <div
                                class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-400 rounded-full border-2 border-white"
                            ></div>
                        </div>
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-slate-800 mb-1">
                                {{ __("Welcome back") }}, {{ user.name }}
                            </h1>
                            <p class="text-slate-600 text-sm">
                                {{
                                    __(
                                        "Manage your applications and preferences"
                                    )
                                }}
                            </p>
                        </div>
                        <div class="hidden sm:flex items-center space-x-3">
                            <div
                                class="bg-white/80 backdrop-blur-sm rounded-xl px-4 py-2 shadow-sm border border-slate-200/60"
                            >
                                <div
                                    class="text-sm font-semibold text-slate-700"
                                >
                                    {{ userRoutes.length }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ __("Applications") }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content Card -->
                    <div
                        class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden"
                    >
                        <!-- Applications Section -->
                        <div class="p-6 border-b border-slate-200/40">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center"
                                    >
                                        <svg
                                            class="w-5 h-5 text-blue-600"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 3v18"
                                            />
                                        </svg>
                                    </div>
                                    <div>
                                        <h2
                                            class="text-lg font-semibold text-slate-800"
                                        >
                                            {{ __("My Applications") }}
                                        </h2>
                                        <p class="text-sm text-slate-600">
                                            {{
                                                __(
                                                    "Access your connected applications"
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>
                                <div class="w-64">
                                    <div class="relative">
                                        <input
                                            v-model="searchTerm"
                                            type="text"
                                            placeholder="Search applications..."
                                            class="w-full pl-9 pr-4 py-2.5 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 text-sm"
                                        />
                                        <svg
                                            class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                            />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Applications Grid -->
                            <div
                                v-if="filteredApplications.length === 0"
                                class="text-center py-12"
                            >
                                <div
                                    class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4"
                                >
                                    <svg
                                        class="w-8 h-8 text-slate-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-8V4a1 1 0 00-1-1h-2a1 1 0 00-1 1v1M9 7h6"
                                        />
                                    </svg>
                                </div>
                                <h3
                                    class="text-lg font-medium text-slate-500 mb-2"
                                >
                                    {{ __("No applications found") }}
                                </h3>
                                <p class="text-sm text-slate-400">
                                    {{ __("Try adjusting your search terms") }}
                                </p>
                            </div>

                            <div
                                v-else
                                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                            >
                                <div
                                    v-for="(app, index) in filteredApplications"
                                    :key="index"
                                    class="group bg-white border border-slate-200 rounded-xl hover:border-blue-300 hover:shadow-md transition-all duration-300"
                                >
                                    <div class="p-5">
                                        <div
                                            class="flex items-center justify-between mb-4"
                                        >
                                            <div
                                                class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-sm"
                                            >
                                                <svg
                                                    class="w-6 h-6 text-white"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"
                                                    />
                                                </svg>
                                            </div>
                                            <button
                                                @click="openApplication(app)"
                                                class="opacity-0 group-hover:opacity-100 w-8 h-8 bg-slate-100 hover:bg-blue-500 text-slate-600 hover:text-white rounded-lg flex items-center justify-center transition-all duration-300"
                                            >
                                                <svg
                                                    class="w-4 h-4"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                                    />
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="mb-4">
                                            <h3
                                                class="font-semibold text-slate-800 mb-2 group-hover:text-blue-600 transition-colors"
                                            >
                                                {{ app.name }}
                                            </h3>
                                            <p
                                                class="text-sm text-slate-600 leading-relaxed"
                                            >
                                                {{
                                                    __(
                                                        "No description available"
                                                    )
                                                }}
                                            </p>
                                        </div>

                                        <button
                                            @click="openApplication(app)"
                                            class="w-full bg-blue-50 text-blue-600 hover:bg-blue-500 hover:text-white py-2.5 px-4 rounded-lg font-medium transition-all duration-300 text-sm flex items-center justify-center space-x-2"
                                        >
                                            <span>{{
                                                __("Launch Application")
                                            }}</span>
                                            <svg
                                                class="w-4 h-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Settings Section -->
                        <div class="p-6">
                            <div class="flex items-center space-x-3 mb-6">
                                <div
                                    class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center"
                                >
                                    <svg
                                        class="w-5 h-5 text-emerald-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                    </svg>
                                </div>
                                <div>
                                    <h2
                                        class="text-lg font-semibold text-slate-800"
                                    >
                                        {{ __("Settings & Preferences") }}
                                    </h2>
                                    <p class="text-sm text-slate-600">
                                        {{ __("Manage your account settings") }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                            >
                                <div
                                    v-for="(setting, index) in userSettings"
                                    :key="'setting-' + index"
                                    class="group bg-white border border-slate-200 rounded-xl hover:border-emerald-300 hover:shadow-md transition-all duration-300"
                                >
                                    <div class="p-5">
                                        <div
                                            class="flex items-center justify-between mb-4"
                                        >
                                            <div
                                                class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center shadow-sm"
                                            >
                                                <svg
                                                    class="w-6 h-6 text-white"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"
                                                    />
                                                </svg>
                                            </div>
                                            <button
                                                @click="openSetting(setting)"
                                                class="opacity-0 group-hover:opacity-100 w-8 h-8 bg-slate-100 hover:bg-emerald-500 text-slate-600 hover:text-white rounded-lg flex items-center justify-center transition-all duration-300"
                                            >
                                                <svg
                                                    class="w-4 h-4"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 5l7 7-7 7"
                                                    />
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="mb-4">
                                            <h3
                                                class="font-semibold text-slate-800 mb-2 group-hover:text-emerald-600 transition-colors"
                                            >
                                                {{ setting.name }}
                                            </h3>
                                            <p
                                                class="text-sm text-slate-600 leading-relaxed"
                                            >
                                                {{
                                                    __(
                                                        "Configure your preferences"
                                                    )
                                                }}
                                            </p>
                                        </div>

                                        <button
                                            @click="openSetting(setting)"
                                            class="w-full bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white py-2.5 px-4 rounded-lg font-medium transition-all duration-300 text-sm flex items-center justify-center space-x-2"
                                        >
                                            <span>{{ __("Configure") }}</span>
                                            <svg
                                                class="w-4 h-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 5l7 7-7 7"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </v-account-layout>
</template>

<script>
import VAccountLayout from "../../../Components/VAccountLayout.vue";
export default {
    components: {
        VAccountLayout,
    },
    data() {
        return {
            refreshing: false,
            searchTerm: "",
        };
    },

    computed: {
        user() {
            return this.$page.props.user;
        },

        userInitials() {
            return `${this.user.name?.[0] || ""}${
                this.user.last_name?.[0] || ""
            }`.toUpperCase();
        },

        userRoutes() {
            return Object.values(this.$page.props.user_routes || {}).sort(
                (a, b) => a.name.localeCompare(b.name)
            );
        },

        userSettings() {
            return Object.values(this.$page.props.user_settings || {}).sort(
                (a, b) => a.name.localeCompare(b.name)
            );
        },

        filteredApplications() {
            if (!this.searchTerm) return this.userRoutes;

            const searchLower = this.searchTerm.toLowerCase();
            return this.userRoutes.filter(
                (app) =>
                    app.name.toLowerCase().includes(searchLower) ||
                    (app.description &&
                        app.description.toLowerCase().includes(searchLower))
            );
        },
    },

    methods: {
        openApplication(app) {
            if (app.route) {
                window.location.href = app.route;
            }
        },

        openSetting(setting) {
            if (setting.route) {
                window.location.href = setting.route;
            }
        },
    },
};
</script>
