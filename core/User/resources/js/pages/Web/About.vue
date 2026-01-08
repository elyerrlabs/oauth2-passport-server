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
        <div class="min-h-screen bg-slate-50 dark:bg-slate-900">
            <!-- Compact Header Section -->
            <div class="px-4 sm:px-6 py-6 lg:px-8">
                <div class="max-w-7xl mx-auto">
                    <!-- Enhanced User Info - More Compact -->
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <div
                                    class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow"
                                >
                                    <span
                                        class="text-white font-semibold text-sm"
                                    >
                                        {{ userInitials }}
                                    </span>
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
                                class="bg-white dark:bg-slate-800 rounded-lg px-3 py-1.5 shadow border border-slate-200 dark:border-slate-700"
                            >
                                <div
                                    class="text-sm font-semibold text-slate-700 dark:text-slate-300 text-center"
                                >
                                    {{ user_routes.length }}
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
                        class="bg-white dark:bg-slate-800 rounded-xl shadow border border-slate-200 dark:border-slate-700 overflow-hidden"
                    >
                        <!-- Applications Section -->
                        <div
                            class="p-5 border-b border-slate-200 dark:border-slate-700"
                        >
                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between mb-5 gap-4"
                            >
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center"
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
                                <div class="w-full sm:w-56">
                                    <div class="relative">
                                        <input
                                            v-model="searchTerm"
                                            type="text"
                                            @input="filteredApplications"
                                            :placeholder="
                                                __('Search applications...')
                                            "
                                            class="w-full pl-8 pr-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:focus:border-blue-400 transition-colors duration-200 text-xs text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400"
                                        />
                                        <i
                                            class="mdi mdi-magnify absolute left-2.5 top-1/2 transform -translate-y-1/2 text-slate-400 dark:text-slate-500 text-sm"
                                        ></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Applications Grid - More Compact -->
                            <div
                                v-show="!filtered_apps.length"
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

                            <div v-show="filtered_apps.length">
                                <v-apps :apps="filtered_apps" />
                            </div>
                        </div>

                        <v-apps
                            :apps="admin_routes"
                            :title="__('Admin Applications')"
                        />

                        <v-apps :apps="user_settings" />

                        <v-apps :apps="admin_settings" :title="__('Admin Settings')"/>
                    </div>

                    <!-- Quick Stats Footer -->
                    <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div
                            class="bg-white dark:bg-slate-800 rounded-lg p-3 border border-slate-200 dark:border-slate-700"
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
                                        {{ user_routes.length }}
                                    </p>
                                </div>
                                <i
                                    class="mdi mdi-rocket-launch-outline text-blue-600 dark:text-blue-400 text-sm"
                                ></i>
                            </div>
                        </div>
                        <div
                            class="bg-white dark:bg-slate-800 rounded-lg p-3 border border-slate-200 dark:border-slate-700"
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
                                        {{ user_settings.length }}
                                    </p>
                                </div>
                                <i
                                    class="mdi mdi-cog-outline text-emerald-600 dark:text-emerald-400 text-sm"
                                ></i>
                            </div>
                        </div>
                        <div
                            class="bg-white dark:bg-slate-800 rounded-lg p-3 border border-slate-200 dark:border-slate-700"
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
                                        {{ user.last_connected }}
                                    </p>
                                </div>
                                <i
                                    class="mdi mdi-clock-check-outline text-purple-600 dark:text-purple-400 text-sm"
                                ></i>
                            </div>
                        </div>
                        <div
                            class="bg-white dark:bg-slate-800 rounded-lg p-3 border border-slate-200 dark:border-slate-700"
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
                                    class="mdi mdi-shield-check-outline text-green-600 dark:text-green-400 text-sm"
                                ></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </v-account-layout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import VAccountLayout from "@/components/VAccountLayout.vue";
import { usePage } from "@inertiajs/vue3";
import VApps from "@/components/VApps.vue";

const page = usePage();
const searchTerm = ref("");

const user = ref([]);
const user_routes = ref([]);
const admin_routes = ref([]);
const user_settings = ref([]);
const admin_settings = ref([]);
const userInitials = ref("");
const filtered_apps = ref([]);

onMounted(() => {
    user.value = page.props.user;
    user_routes.value = page.props.user_routes;
    filtered_apps.value = page.props.user_routes;
    admin_routes.value = page.props.admin_routes;
    user_settings.value = page.props.user_settings;
    admin_settings.value = page.props.admin_settings;
    userInitials.value = `${user.value.name?.[0] || ""}${
        user.value.last_name?.[0] || ""
    }`.toUpperCase();
});

const filteredApplications = () => {
    if (!searchTerm.value) {
        filtered_apps.value = user_routes.value;
        return;
    }

    filtered_apps.value = user_routes.value.filter((app) =>
        app.name.toLowerCase().includes(searchTerm.value.toLowerCase())
    );
};
</script>
