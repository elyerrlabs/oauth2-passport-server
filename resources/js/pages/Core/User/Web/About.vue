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
        <div
            class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50/30 dark:from-slate-900 dark:to-slate-800/50"
        >
            <!-- Compact Header Section -->
            <div class="px-4 sm:px-6 py-6 lg:px-8">
                <div class="max-w-7xl mx-auto">
                    <!-- Enhanced User Info - More Compact -->
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md"
                                >
                                    <span
                                        class="text-white font-semibold text-sm"
                                        >{{ userInitials }}</span
                                    >
                                </div>
                                <div
                                    class="absolute -bottom-1 -right-1 w-3 h-3 bg-emerald-400 rounded-full border-2 border-white dark:border-slate-900"
                                ></div>
                            </div>
                            <div>
                                <h1
                                    class="text-xl font-bold text-slate-800 dark:text-white mb-0.5"
                                >
                                    {{ __("Welcome back") }}, {{ user.name }}
                                </h1>
                                <p
                                    class="text-slate-500 dark:text-slate-400 text-xs"
                                >
                                    {{
                                        __(
                                            "Manage your applications and preferences"
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div
                                class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-lg px-3 py-1.5 shadow-sm border border-slate-200/60 dark:border-slate-700"
                            >
                                <div
                                    class="text-sm font-semibold text-slate-700 dark:text-slate-300 text-center"
                                >
                                    {{ userRoutes.length }}
                                </div>
                                <div
                                    class="text-xs text-slate-500 dark:text-slate-400"
                                >
                                    {{ __("Apps") }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content Card - More Compact -->
                    <div
                        class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-xl shadow-sm border border-slate-200/60 dark:border-slate-700 overflow-hidden"
                    >
                        <!-- Applications Section -->
                        <div
                            class="p-5 border-b border-slate-200/40 dark:border-slate-700/40"
                        >
                            <div class="flex items-center justify-between mb-5">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-8 h-8 bg-blue-100 dark:bg-blue-900/40 rounded-lg flex items-center justify-center"
                                    >
                                        <i
                                            class="mdi mdi-rocket-launch-outline text-blue-600 dark:text-blue-400 text-sm"
                                        ></i>
                                    </div>
                                    <div>
                                        <h2
                                            class="text-base font-semibold text-slate-800 dark:text-white"
                                        >
                                            {{ __("My Applications") }}
                                        </h2>
                                        <p
                                            class="text-xs text-slate-500 dark:text-slate-400"
                                        >
                                            {{
                                                __("Access your connected apps")
                                            }}
                                        </p>
                                    </div>
                                </div>
                                <div class="w-56">
                                    <div class="relative">
                                        <input
                                            v-model="searchTerm"
                                            type="text"
                                            :placeholder="
                                                __('Search applications...')
                                            "
                                            class="w-full pl-8 pr-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-400 transition-all duration-200 text-xs text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400"
                                        />
                                        <i
                                            class="mdi mdi-magnify absolute left-2.5 top-1/2 transform -translate-y-1/2 text-slate-400 dark:text-slate-500 text-sm"
                                        ></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Applications Grid - More Compact -->
                            <div
                                v-if="filteredApplications.length === 0"
                                class="text-center py-8"
                            >
                                <div
                                    class="w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded-xl flex items-center justify-center mx-auto mb-3"
                                >
                                    <i
                                        class="mdi mdi-apps text-slate-400 dark:text-slate-500 text-lg"
                                    ></i>
                                </div>
                                <h3
                                    class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1"
                                >
                                    {{ __("No applications found") }}
                                </h3>
                                <p
                                    class="text-xs text-slate-400 dark:text-slate-500"
                                >
                                    {{ __("Try adjusting your search terms") }}
                                </p>
                            </div>

                            <div
                                v-else
                                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3"
                            >
                                <div
                                    v-for="(app, index) in filteredApplications"
                                    :key="index"
                                    class="group bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg hover:border-blue-300 dark:hover:border-blue-400 hover:shadow-sm transition-all duration-200"
                                >
                                    <div
                                        class="p-4 bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 rounded-lg"
                                    >
                                        <div
                                            class="flex items-center justify-between mb-3"
                                        >
                                            <div
                                                class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center shadow-xs"
                                            >
                                                <i
                                                    class="mdi text-white text-xl"
                                                    :class="getAppIcon(app)"
                                                ></i>
                                            </div>
                                            <button
                                                @click="openApplication(app)"
                                                class="opacity-0 group-hover:opacity-100 w-6 h-6 bg-white/20 hover:bg-white/30 text-white rounded flex items-center justify-center transition-all duration-200 backdrop-blur-sm"
                                            >
                                                <i
                                                    class="mdi mdi-arrow-top-right text-xs"
                                                ></i>
                                            </button>
                                        </div>

                                        <div class="mb-3 text-center">
                                            <h3
                                                class="font-medium text-white text-sm mb-1 transition-colors line-clamp-1"
                                            >
                                                {{ app.name }}
                                            </h3>
                                            <p
                                                class="text-blue-100 text-xs opacity-80 line-clamp-2"
                                            >
                                                {{
                                                    app.description ||
                                                    __(
                                                        "No description available"
                                                    )
                                                }}
                                            </p>
                                        </div>

                                        <button
                                            @click="openApplication(app)"
                                            class="w-full bg-white/20 hover:bg-white/30 text-white py-2 px-3 rounded-md font-medium transition-all duration-200 text-xs flex items-center justify-center space-x-1 backdrop-blur-sm"
                                        >
                                            <span>{{ __("Launch") }}</span>
                                            <i
                                                class="mdi mdi-arrow-right text-xs"
                                            ></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Settings Section - More Compact -->
                        <div class="p-5">
                            <div class="flex items-center space-x-3 mb-5">
                                <div
                                    class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg flex items-center justify-center"
                                >
                                    <i
                                        class="mdi mdi-cog-outline text-emerald-600 dark:text-emerald-400 text-sm"
                                    ></i>
                                </div>
                                <div>
                                    <h2
                                        class="text-base font-semibold text-slate-800 dark:text-white"
                                    >
                                        {{ __("Settings") }}
                                    </h2>
                                    <p
                                        class="text-xs text-slate-500 dark:text-slate-400"
                                    >
                                        {{ __("Manage your account settings") }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3"
                            >
                                <div
                                    v-for="(setting, index) in userSettings"
                                    :key="'setting-' + index"
                                    class="group bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg hover:border-emerald-300 dark:hover:border-emerald-400 hover:shadow-sm transition-all duration-200"
                                >
                                    <div
                                        class="p-4 bg-gradient-to-br from-emerald-500 to-green-600 dark:from-emerald-600 dark:to-emerald-700 rounded-lg"
                                    >
                                        <div
                                            class="flex items-center justify-between mb-3"
                                        >
                                            <div
                                                class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center shadow-xs"
                                            >
                                                <i
                                                    class="mdi text-white text-xl"
                                                    :class="
                                                        getSettingIcon(setting)
                                                    "
                                                ></i>
                                            </div>
                                            <button
                                                @click="
                                                    openApplication(setting)
                                                "
                                                class="opacity-0 group-hover:opacity-100 w-6 h-6 bg-white/20 hover:bg-white/30 text-white rounded flex items-center justify-center transition-all duration-200 backdrop-blur-sm"
                                            >
                                                <i
                                                    class="mdi mdi-chevron-right text-xs"
                                                ></i>
                                            </button>
                                        </div>

                                        <div class="mb-3">
                                            <h3
                                                class="font-medium text-white text-sm mb-1 transition-colors line-clamp-1"
                                            >
                                                {{ setting.name }}
                                            </h3>
                                            <p
                                                class="text-emerald-100 text-xs opacity-80 line-clamp-2"
                                            >
                                                {{
                                                    setting.description ||
                                                    __(
                                                        "Manage your preferences"
                                                    )
                                                }}
                                            </p>
                                        </div>

                                        <button
                                            @click="openApplication(setting)"
                                            class="w-full bg-white/20 hover:bg-white/30 text-white py-2 px-3 rounded-md font-medium transition-all duration-200 text-xs flex items-center justify-center space-x-1 backdrop-blur-sm"
                                        >
                                            <span>{{ __("Configure") }}</span>
                                            <i
                                                class="mdi mdi-chevron-right text-xs"
                                            ></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats Footer -->
                    <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div
                            class="bg-white/60 dark:bg-slate-800/60 backdrop-blur-sm rounded-lg p-3 border border-slate-200/60 dark:border-slate-700"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <p
                                        class="text-xs text-slate-500 dark:text-slate-400 mb-1"
                                    >
                                        {{ __("Total Apps") }}
                                    </p>
                                    <p
                                        class="text-sm font-semibold text-slate-700 dark:text-slate-300"
                                    >
                                        {{ userRoutes.length }}
                                    </p>
                                </div>
                                <i
                                    class="mdi mdi-rocket-launch-outline text-blue-500 dark:text-blue-400 text-sm"
                                ></i>
                            </div>
                        </div>
                        <div
                            class="bg-white/60 dark:bg-slate-800/60 backdrop-blur-sm rounded-lg p-3 border border-slate-200/60 dark:border-slate-700"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <p
                                        class="text-xs text-slate-500 dark:text-slate-400 mb-1"
                                    >
                                        {{ __("Settings") }}
                                    </p>
                                    <p
                                        class="text-sm font-semibold text-slate-700 dark:text-slate-300"
                                    >
                                        {{ userSettings.length }}
                                    </p>
                                </div>
                                <i
                                    class="mdi mdi-cog-outline text-emerald-500 dark:text-emerald-400 text-sm"
                                ></i>
                            </div>
                        </div>
                        <div
                            class="bg-white/60 dark:bg-slate-800/60 backdrop-blur-sm rounded-lg p-3 border border-slate-200/60 dark:border-slate-700"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <p
                                        class="text-xs text-slate-500 dark:text-slate-400 mb-1"
                                    >
                                        {{ __("Last Login") }}
                                    </p>
                                    <p
                                        class="text-sm font-semibold text-slate-700 dark:text-slate-300"
                                    >
                                        {{ formatLastLogin() }}
                                    </p>
                                </div>
                                <i
                                    class="mdi mdi-clock-check-outline text-purple-500 dark:text-purple-400 text-sm"
                                ></i>
                            </div>
                        </div>
                        <div
                            class="bg-white/60 dark:bg-slate-800/60 backdrop-blur-sm rounded-lg p-3 border border-slate-200/60 dark:border-slate-700"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <p
                                        class="text-xs text-slate-500 dark:text-slate-400 mb-1"
                                    >
                                        {{ __("Status") }}
                                    </p>
                                    <p
                                        class="text-sm font-semibold text-green-600 dark:text-green-400"
                                    >
                                        {{ __("Active") }}
                                    </p>
                                </div>
                                <i
                                    class="mdi mdi-shield-check-outline text-green-500 dark:text-green-400 text-sm"
                                ></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </v-account-layout>
</template>

<script>
import VAccountLayout from "@/layouts/VAccountLayout.vue";
import { router } from "@inertiajs/vue3";
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
        getAppIcon(app) {
            
            const iconMap = {
                dashboard: "mdi-view-dashboard-outline",
                admin: "mdi-shield-account-outline",
                reports: "mdi-chart-bar",
                analytics: "mdi-chart-line",
                settings: "mdi-cog-outline",
                users: "mdi-account-group-outline",
                files: "mdi-folder-outline",
                messages: "mdi-email-outline",
                calendar: "mdi-calendar-outline",
                tasks: "mdi-checkbox-marked-outline",
                finance: "mdi-cash-multiple",
                inventory: "mdi-package-variant",
                crm: "mdi-account-tie-outline",
                hr: "mdi-briefcase-outline",
                projects: "mdi-briefcase-outline",
                support: "mdi-headset",
                marketing: "mdi-bullhorn-outline",
                sales: "mdi-trending-up",
                default: "mdi-application-outline",
            };

            const appName = app.name?.toLowerCase() || "";
            const appIcon = app.icon || "";

            for (const [key, icon] of Object.entries(iconMap)) {
                if (appName.includes(key) || appIcon.includes(key)) {
                    return icon;
                }
            }

            return iconMap.default;
        },

        getSettingIcon(setting) {
            const iconMap = {
                profile: "mdi-account-outline",
                password: "mdi-lock-outline",
                security: "mdi-shield-account-outline",
                notifications: "mdi-bell-outline",
                privacy: "mdi-eye-off-outline",
                preferences: "mdi-tune",
                billing: "mdi-credit-card-outline",
                subscription: "mdi-star-outline",
                integrations: "mdi-puzzle-outline",
                api: "mdi-key-chain",
                team: "mdi-account-group-outline",
                appearance: "mdi-palette-outline",
                language: "mdi-translate",
                backup: "mdi-cloud-upload-outline",
                default: "mdi-cog-outline",
            };

            const settingName = setting.name?.toLowerCase() || "";
            const settingIcon = setting.icon || "";

            for (const [key, icon] of Object.entries(iconMap)) {
                if (settingName.includes(key) || settingIcon.includes(key)) {
                    return icon;
                }
            }

            return iconMap.default;
        },

        openApplication(app) {
            if (app.route) {
                router.visit(app.route);
            }
        },

        formatLastLogin() {
            return "Today";
        },
    },
};
</script>

<style scoped>
.line-clamp-1 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 1;
}

.line-clamp-2 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
}
</style>
