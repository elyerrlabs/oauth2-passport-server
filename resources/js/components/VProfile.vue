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
    <div class="relative" ref="menuContainer">
        <!-- Menu Button -->
        <button
            @click="toggleMenu"
            class="flex items-center p-1 md:p-2 bg-white dark:bg-gray-800 cursor-pointer rounded-xl shadow-sm hover:shadow-md hover:border-gray-400 dark:hover:border-gray-500 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 group"
        >
            <!-- User Avatar -->
            <div class="shrink-0">
                <div
                    class="p-2 rounded-full bg-linear-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white shadow-inner group-hover:shadow-md transition-shadow"
                >
                    <template v-if="user?.id">
                        <span class="text-sm">
                            {{ getUserInitials }}
                        </span>
                    </template>
                    <template v-else>
                        <i class="mdi mdi-account text-sm"></i>
                    </template>
                </div>
            </div>

            <!-- User Info (Desktop) -->
            <div class="hidden lg:block text-left">
                <div
                    class="text-sm font-medium text-gray-700 dark:text-gray-300 leading-tight"
                >
                    <template v-if="user?.id">
                        {{ user.name }} {{ user.last_name }}
                    </template>
                    <template v-else>
                        {{ __("Guest") }}
                    </template>
                </div>
                <div
                    class="text-xs text-gray-500 dark:text-gray-400 leading-tight"
                >
                    <template v-if="user?.id">
                        {{ user.email }}
                    </template>
                    <template v-else>
                        {{ __("Sign in") }}
                    </template>
                </div>
            </div>

            <!-- Chevron Icon -->
            <div class="hidden lg:block shrink-0">
                <i
                    class="mdi text-gray-400 transition-transform duration-200"
                    :class="menuOpen ? 'mdi-chevron-up' : 'mdi-chevron-down'"
                ></i>
            </div>

            <span class="sr-only">{{ __("User menu") }}</span>
        </button>

        <!-- Dropdown Menu -->
        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95 translate-y-2"
            enter-to-class="opacity-100 scale-100 translate-y-0"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 scale-100 translate-y-0"
            leave-to-class="opacity-0 scale-95 translate-y-2"
        >
            <div
                v-if="menuOpen"
                class="absolute right-0 mt-2 w-72 sm:w-80 bg-white dark:bg-gray-800 rounded-xl shadow-xl ring-1 ring-black/5 dark:ring-white/10 z-50 overflow-hidden"
            >
                <!-- User Info Section -->
                <div
                    v-if="user?.id"
                    class="p-4 bg-linear-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 border-b border-gray-100 dark:border-gray-700"
                >
                    <div class="flex items-center space-x-3">
                        <div class="shrink-0">
                            <div
                                class="w-12 h-12 rounded-full bg-linear-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white shadow-lg"
                            >
                                <span class="text-lg font-semibold">
                                    {{ getUserInitials }}
                                </span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div
                                class="font-semibold text-gray-900 dark:text-white truncate"
                            >
                                {{ user.name }} {{ user.last_name }}
                            </div>
                            <div
                                class="text-sm text-gray-600 dark:text-gray-400 truncate"
                            >
                                {{ user.email }}
                            </div>
                            <div
                                class="flex items-center mt-1 text-xs text-blue-600 dark:text-blue-400"
                            >
                                <i class="mdi mdi-check-circle mr-1"></i>
                                {{ __("Verified account") }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Guest Info Section -->
                <div
                    v-else
                    class="p-4 bg-linear-to-r from-gray-50 to-blue-50 dark:from-gray-800 dark:to-blue-900/20 border-b border-gray-100 dark:border-gray-700"
                >
                    <div class="flex items-center space-x-3">
                        <div class="shrink-0">
                            <div
                                class="w-12 h-12 rounded-full bg-gray-400 dark:bg-gray-600 flex items-center justify-center text-white shadow-lg"
                            >
                                <i class="mdi mdi-account-outline text-xl"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div
                                class="font-semibold text-gray-900 dark:text-white"
                            >
                                {{ __("Welcome!") }}
                            </div>
                            <div
                                class="text-sm text-gray-600 dark:text-gray-400"
                            >
                                {{ __("Sign in to your account") }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Menu Options -->
                <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                    <!-- Home -->
                    <li>
                        <a
                            href="/"
                            @click.prevent="homePage"
                            class="w-full text-left px-4 py-3 hover:bg-white dark:hover:bg-gray-700 flex items-center transition-all duration-200 group cursor-pointer"
                        >
                            <div
                                class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform"
                            >
                                <i
                                    class="mdi mdi-home text-blue-600 dark:text-blue-400 text-lg"
                                ></i>
                            </div>
                            <div>
                                <div
                                    class="font-medium text-gray-900 dark:text-white"
                                >
                                    {{ __("Home") }}
                                </div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Back to homepage") }}
                                </div>
                            </div>
                        </a>
                    </li>

                    <!-- My Account (Authenticated only) -->
                    <li v-if="user?.id">
                        <a
                            :href="userDashboardRoute"
                            @click.prevent="myAccount"
                            class="w-full text-left px-4 py-3 hover:bg-white dark:hover:bg-gray-700 flex items-center transition-all duration-200 group cursor-pointer"
                        >
                            <div
                                class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform"
                            >
                                <i
                                    class="mdi mdi-account-cog text-green-600 dark:text-green-400 text-lg"
                                ></i>
                            </div>
                            <div>
                                <div
                                    class="font-medium text-gray-900 dark:text-white"
                                >
                                    {{ __("My Account") }}
                                </div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Manage your profile") }}
                                </div>
                            </div>
                        </a>
                    </li>

                    <!-- Login (Unauthenticated only) -->
                    <li class="block lg:hidden" v-if="!user?.id">
                        <a
                            @click="open($page.props.auth_routes.login)"
                            class="w-full text-left px-4 py-3 hover:bg-white dark:hover:bg-gray-700 flex items-center transition-all duration-200 group cursor-pointer"
                        >
                            <div
                                class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform"
                            >
                                <i
                                    class="mdi mdi-login text-blue-600 dark:text-blue-400 text-lg"
                                ></i>
                            </div>
                            <div>
                                <div
                                    class="font-medium text-gray-900 dark:text-white"
                                >
                                    {{ __("Sign In") }}
                                </div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Access your account") }}
                                </div>
                            </div>
                        </a>
                    </li>

                    <li
                        class="block lg:hidden"
                        v-if="$page.props.allow_register && !user?.id"
                    >
                        <a
                            @click="open($page.props.auth_routes.register)"
                            class="w-full text-left px-4 py-3 hover:bg-white dark:hover:bg-gray-700 flex items-center transition-all duration-200 group cursor-pointer"
                        >
                            <div
                                class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform"
                            >
                                <i
                                    class="mdi mdi-login text-blue-600 dark:text-blue-400 text-lg"
                                ></i>
                            </div>
                            <div>
                                <div
                                    class="font-medium text-gray-900 dark:text-white"
                                >
                                    {{ __("Sign up") }}
                                </div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Create a new account") }}
                                </div>
                            </div>
                        </a>
                    </li>

                    <!-- Logout (Authenticated only) -->
                    <li v-if="user?.id">
                        <a
                            :href="logoutRoute"
                            @click.prevent="goTo(logoutRoute)"
                            class="w-full text-left px-4 py-3 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center transition-all duration-200 group cursor-pointer"
                        >
                            <div
                                class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform"
                            >
                                <i
                                    class="mdi mdi-logout text-red-600 dark:text-red-400 text-lg"
                                ></i>
                            </div>
                            <div>
                                <div
                                    class="font-medium text-gray-900 dark:text-white"
                                >
                                    {{ __("Sign Out") }}
                                </div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("End your session") }}
                                </div>
                            </div>
                        </a>
                    </li>
                </ul>

                <!-- Footer -->
                <div
                    class="px-4 py-3 bg-white dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700"
                >
                    <div
                        class="text-xs text-gray-500 dark:text-gray-400 text-center"
                    >
                        {{ __("Secure connection") }}
                        <i class="mdi mdi-shield-check text-green-500 ml-1"></i>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    data() {
        return {
            menuOpen: false,
        };
    },
    computed: {
        user() {
            return this.$page.props.user;
        },
        loginRoute() {
            return this.$page.props.auth_routes["login"];
        },
        logoutRoute() {
            return this.$page.props.auth_routes["logout"];
        },
        userDashboardRoute() {
            return this.$page.props.user_dashboard?.route || "#";
        },
        getUserInitials() {
            if (!this.user?.name) return "?";
            const names = [this.user.name, this.user.last_name].filter(Boolean);
            return names
                .map((name) => name.charAt(0).toUpperCase())
                .join("")
                .substring(0, 2);
        },
    },
    methods: {
        toggleMenu() {
            this.menuOpen = !this.menuOpen;
        },
        closeMenu() {
            this.menuOpen = false;
        },
        goTo(url) {
            this.closeMenu();
            window.location.href = url;
        },
        homePage() {
            this.closeMenu();
            window.location.href = "/";
        },
        myAccount() {
            this.closeMenu();
            window.location.href = this.userDashboardRoute;
        },
        handleClickOutside(event) {
            if (
                this.menuOpen &&
                this.$refs.menuContainer &&
                !this.$refs.menuContainer.contains(event.target)
            ) {
                this.closeMenu();
            }
        },
        handleEscapeKey(event) {
            if (event.key === "Escape" && this.menuOpen) {
                this.closeMenu();
            }
        },

        open(url) {
            const currentUrl = window.location.href;

            const path = url.startsWith("/")
                ? url
                : new URL(url, window.location.origin).pathname;

            if (
                path.startsWith("/auth/login") ||
                path.startsWith("/auth/register")
            ) {
                const redirectUrl = new URL(url, window.location.origin);
                redirectUrl.searchParams.set("redirect_to", currentUrl);
                window.location.href = redirectUrl.toString();
            } else {
                window.location.href = url;
            }
        },
    },
    mounted() {
        document.addEventListener("click", this.handleClickOutside);
        document.addEventListener("keydown", this.handleEscapeKey);
    },
    beforeUnmount() {
        document.removeEventListener("click", this.handleClickOutside);
        document.removeEventListener("keydown", this.handleEscapeKey);
    },
};
</script>
