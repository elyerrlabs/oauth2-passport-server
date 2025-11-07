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
    <div class="min-h-screen bg-gray-50 flex">
        <!-- Sidebar Overlay (Mobile) -->
        <div
            v-if="isSidebarOpen"
            class="fixed inset-0 bg-black/80 z-30 lg:hidden"
            @click="toggleMenu"
        ></div>

        <!-- Enhanced Sidebar - Fixed on desktop -->
        <aside
            :class="[
                'fixed lg:fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-xl transform transition-transform duration-300 ease-in-out lg:transform-none lg:translate-x-0 border-r border-gray-200 flex flex-col',
                isSidebarOpen ? 'translate-x-0' : '-translate-x-full',
            ]"
        >
            <!-- User Profile Section -->
            <div
                class="p-4 border-b border-gray-200 flex-shrink-0"
                v-if="$page.props.user"
            >
                <div class="flex items-center space-x-3">
                    <div
                        class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-lg"
                    >
                        {{ userInitials }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">
                            {{ user.name }}
                        </p>
                        <p class="text-sm text-gray-500 truncate">
                            {{ user.email }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Scrollable Navigation -->
            <div class="flex-1 overflow-y-auto">
                <nav class="p-4 space-y-2">
                    <!-- Account Section -->
                    <div>
                        <h3
                            class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2"
                        >
                            {{ __("Account") }}
                        </h3>
                        <button
                            @click="open($page.props.user_dashboard)"
                            class="w-full flex items-center space-x-3 px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200"
                        >
                            <div
                                class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center"
                            >
                                <i
                                    :class="[
                                        'mdi',
                                        $page.props.user_dashboard.icon,
                                        'text-white text-sm',
                                    ]"
                                ></i>
                            </div>
                            <span>{{
                                __($page.props.user_dashboard.name)
                            }}</span>
                        </button>
                    </div>

                    <!-- Developers Section -->
                    <div v-if="developers.show">
                        <h3
                            class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-6"
                        >
                            {{ __(developers.name) }}
                        </h3>
                        <button
                            v-for="(item, index) in developers.menu"
                            :key="index"
                            @click="open(item)"
                            class="w-full flex items-center space-x-3 px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200"
                        >
                            <div
                                class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center"
                            >
                                <i
                                    :class="[
                                        'mdi',
                                        item.icon,
                                        'text-white text-sm',
                                    ]"
                                ></i>
                            </div>
                            <span>{{ __(item.name) }}</span>
                        </button>
                    </div>

                    <!-- Dashboards Section -->
                    <div v-if="admin_dashboard.length">
                        <h3
                            class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-6"
                        >
                            {{ __("Dashboards") }}
                        </h3>
                        <button
                            v-for="(item, index) in admin_dashboard"
                            :key="index"
                            @click="open(item)"
                            class="w-full flex items-center space-x-3 px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200"
                        >
                            <div
                                class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center"
                            >
                                <i
                                    :class="[
                                        'mdi',
                                        item.icon,
                                        'text-white text-sm',
                                    ]"
                                ></i>
                            </div>
                            <span>{{ __(item.name) }}</span>
                        </button>
                    </div>

                    <!-- Policies Section -->
                    <div class="pt-4 border-t border-gray-200">
                        <button
                            v-for="(item, index) in $page.props.policies"
                            :key="index"
                            @click="open(item)"
                            v-show="item.show"
                            class="w-full flex items-center space-x-3 px-3 py-2 text-sm font-medium text-gray-500 rounded-lg hover:bg-gray-100 hover:text-gray-700 transition-colors duration-200"
                        >
                            <div
                                class="w-8 h-8 bg-gray-400 rounded-lg flex items-center justify-center"
                            >
                                <i
                                    :class="[
                                        'mdi',
                                        item.icon,
                                        'text-white text-sm',
                                    ]"
                                ></i>
                            </div>
                            <span>{{ __(item.name) }}</span>
                        </button>
                    </div>
                </nav>
            </div>

            <!-- Footer -->
            <div class="border-t border-gray-200 p-4 flex-shrink-0">
                <p class="text-xs text-center text-gray-500">
                    &copy; {{ new Date().getFullYear() }}
                    {{ $page.props.org_name }}
                </p>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 lg:ml-64">
            <!-- Enhanced Header -->
            <header
                class="bg-white shadow-sm border-b border-gray-200 z-20 sticky top-0"
            >
                <div
                    class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8"
                >
                    <div class="flex items-center space-x-4">
                        <!-- Mobile menu button -->
                        <button
                            @click="toggleMenu"
                            class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors duration-200"
                        >
                            <i class="mdi mdi-menu text-lg"></i>
                        </button>

                        <!-- App Title -->
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center"
                            >
                                <i
                                    class="mdi mdi-view-dashboard text-white text-sm"
                                ></i>
                            </div>
                            <h1 class="text-xl font-semibold text-gray-900">
                                {{ app_name }}
                            </h1>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <v-notification />
                        <v-profile />
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto bg-gray-50">
                <div class="p-4 sm:p-6 lg:p-8">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<script>
import VProfile from"@/components/VProfile.vue";
import VNotification from "@/components/VNotification.vue";

export default {
    components: {
        VProfile,
        VNotification,
    },

    data() {
        return {
            isSidebarOpen: false,
            user: {},
            app_name: "",
            menus: [],
            admin_dashboard: [],
            transaction_dashboard: [],
            partner_dashboard: [],
            settings: {},
            developers: [],
            ecommerce_dashboard: [],
        };
    },

    computed: {
        userInitials() {
            if (!this.user || !this.user.name) return "U";
            return this.user.name
                .split(" ")
                .map((n) => n[0])
                .join("")
                .toUpperCase();
        },
    },

    created() {
        this.user = this.$page.props.user ?? {};
        this.app_name = this.$page.props.app_name ?? "";
        this.menus = this.$page.props.user_routes ?? [];
        this.admin_dashboard = this.$page.props.admin_dashboard ?? [];
        this.developers = this.$page.props.developers ?? [];
    },

    mounted() {
        this.setupEventListeners();
    },

    methods: {
        open(item) {
            window.location.href = item.route;
            // Close sidebar on mobile after navigation
            if (window.innerWidth < 1024) {
                this.isSidebarOpen = false;
            }
        },

        toggleMenu() {
            this.isSidebarOpen = !this.isSidebarOpen;
        },

        handleResize() {
            if (window.innerWidth >= 1024) {
                this.isSidebarOpen = false;
            }
        },

        setupEventListeners() {
            window.addEventListener("resize", this.handleResize);
        },
    },

    beforeUnmount() {
        window.removeEventListener("resize", this.handleResize);
    },
};
</script>

<style scoped>
/* Custom scrollbar for sidebar */
.aside-scrollbar::-webkit-scrollbar {
    width: 4px;
}

.aside-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.aside-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.aside-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
