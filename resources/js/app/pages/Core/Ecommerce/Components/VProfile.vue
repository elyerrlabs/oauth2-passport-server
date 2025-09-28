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
    <div class="relative text-center p-2 hidden md:block" ref="menuContainer">
        <!-- Menu Button -->
        <button
            @click="toggleMenu"
            class="px-3 py-2 bg-purple-600 text-white rounded-full shadow-md hover:bg-purple-700 hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-400 relative cursor-pointer"
        >
            <i class="mdi mdi-account-circle text-2xl"></i>
            <span class="sr-only">{{ __("Show the menu") }}</span>
        </button>

        <!-- Dropdown Menu -->
        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="menuOpen"
                class="absolute right-0 mt-3 w-64 bg-white rounded-xl shadow-xl ring-1 ring-black/5 z-50 overflow-hidden"
            >
                <!-- User Info -->
                <div
                    v-if="user?.id"
                    class="flex items-center p-4 border-b border-gray-100 bg-gray-50"
                >
                    <div class="flex-shrink-0 mr-3">
                        <div
                            class="w-12 h-12 rounded-full bg-purple-500 flex items-center justify-center text-white shadow-inner"
                        >
                            <i class="mdi mdi-account text-2xl"></i>
                        </div>
                    </div>
                    <div class="text-left">
                        <div class="font-semibold text-gray-800">
                            {{ user.name }} {{ user.last_name }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ user.email }}
                        </div>
                    </div>
                </div>

                <!-- Menu Options -->
                <ul class="divide-y divide-gray-100">
                    <li>
                        <a
                            href="/"
                            @click.prevent="homePage"
                            class="w-full text-left px-4 py-3 hover:bg-purple-50 flex items-center transition cursor-pointer"
                        >
                            <i class="mdi mdi-home text-purple-600 mr-3"></i>
                            {{ __("Home page") }}
                        </a>
                    </li>

                    <li v-if="user?.id">
                        <a
                            :href="userDashboardRoute"
                            @click.prevent="myAccount"
                            class="w-full text-left px-4 py-3 hover:bg-purple-50 flex items-center transition cursor-pointer"
                        >
                            <i
                                class="mdi mdi-account-cog text-purple-600 mr-3"
                            ></i>
                            {{ __("My account") }}
                        </a>
                    </li>

                    <li v-if="!user?.id">
                        <a
                            :href="loginRoute"
                            @click.prevent="goTo(loginRoute)"
                            class="w-full text-left px-4 py-3 hover:bg-purple-50 flex items-center transition cursor-pointer"
                        >
                            <i class="mdi mdi-login text-purple-600 mr-3"></i>
                            {{ __("Login") }}
                        </a>
                    </li>

                    <li v-if="user?.id">
                        <a
                            :href="logoutRoute"
                            @click.prevent="goTo(logoutRoute)"
                            class="w-full text-left px-4 py-3 hover:bg-red-50 flex items-center text-red-600 transition cursor-pointer"
                        >
                            <i class="mdi mdi-logout text-red-600 mr-3"></i>
                            {{ __("Logout") }}
                        </a>
                    </li>
                </ul>
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
    },
    mounted() {
        document.addEventListener("click", this.handleClickOutside);
    },
    beforeUnmount() {
        document.removeEventListener("click", this.handleClickOutside);
    },
};
</script>
