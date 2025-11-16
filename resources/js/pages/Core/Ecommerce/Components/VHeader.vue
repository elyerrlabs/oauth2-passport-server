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
    <!-- Header -->
    <header
        class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-50 transition-colors duration-300"
    >
        <div class="container-fluid mx-auto px-4 py-1">
            <div class="flex items-center justify-between">
                <!-- Logo and Mobile Menu -->
                <div class="flex items-center">
                    <!-- Logo -->
                    <a
                        :href="$page.props.routes.dashboard"
                        class="lg:text-2xl font-bold text-primary-700 dark:text-purple-400 flex items-center"
                    >
                        <i class="fas fa-store mr-2"></i>
                        <span class="hidden lg:block">
                            {{ $page.props.app_name }}
                        </span>
                    </a>
                </div>

                <!-- Search with Categories Dropdown -->
                <v-search />

                <!-- Right side icons -->
                <div class="flex items-center space-x-2 md:space-x-4">
                    <v-theme />
                    <!-- Notifications -->
                    <div class="hidden md:block">
                        <v-notification v-if="$page.props.user?.id" />
                    </div>

                    <!-- Cart -->
                    <v-cart />

                    <!-- Mobile Menu Button -->
                    <button
                        class="h-8 w-8 text-gray-200 bg-purple-700 dark:bg-purple-600 rounded-full hover:text-white hover:bg-purple-800 dark:hover:bg-purple-700 relative cursor-pointer md:hidden transition-colors duration-300"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                    >
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <v-profile />
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile menu -->
    <div
        class="fixed inset-0 z-51 bg-white dark:bg-gray-800 flex flex-col md:hidden mobile-menu transition-colors duration-300"
        :class="{ open: mobileMenuOpen }"
    >
        <div
            class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700"
        >
            <a
                href="#"
                class="md:text-xl font-bold text-primary-700 dark:text-purple-400 flex items-center"
            >
                <i class="fas fa-store mr-2"></i>{{ $page.props.app_name }}
            </a>
            <button
                class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors"
                @click="mobileMenuOpen = false"
            >
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto py-4">
            <div class="px-4 space-y-2">
                <a
                    href="#"
                    class="block py-3 px-4 rounded-lg bg-primary-50 dark:bg-purple-900/30 text-primary-700 dark:text-purple-400 font-medium transition-colors"
                >
                    <i class="fas fa-th-large mr-3"></i> Categories
                </a>
                <v-categories />
            </div>

            <div
                class="border-t border-gray-200 dark:border-gray-700 my-4"
            ></div>

            <!-- User menu options moved here for mobile -->
            <div class="px-4 space-y-2">
                <a
                    :href="$page.props.auth_routes.dashboard"
                    class="block py-3 px-4 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors"
                >
                    <i
                        class="fas fa-user-circle mr-3 text-primary-600 dark:text-purple-400"
                    ></i>
                    {{ __("My Profile") }}
                </a>
                <a
                    :href="$page.props.ecommerce_orders.route"
                    class="block py-3 px-4 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors"
                >
                    <i
                        class="fas fa-shopping-bag mr-3 text-primary-600 dark:text-purple-400"
                    ></i>
                    {{ __("My Orders") }}
                </a>
                <a
                    :href="$page.props.ecommerce_checkout.route"
                    class="block py-3 px-4 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors"
                >
                    <i
                        class="fas fa-heart mr-3 text-primary-600 dark:text-purple-400"
                    ></i>
                    {{ __("Checkout") }}
                </a>
                <a
                    :href="$page.props.auth_routes.dashboard"
                    class="block py-3 px-4 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors"
                >
                    <i
                        class="fas fa-cog mr-3 text-primary-600 dark:text-purple-400"
                    ></i>
                    {{ __("Settings") }}
                </a>
            </div>

            <div
                class="border-t border-gray-200 dark:border-gray-700 my-4"
            ></div>

            <div class="px-4">
                <a
                    :href="$page.props.auth_routes.logout"
                    class="block py-3 px-4 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-red-600 dark:text-red-400 transition-colors"
                >
                    <i class="fas fa-sign-out-alt mr-3"></i> {{ __("Logout") }}
                </a>
            </div>
        </div>
    </div>

    <!-- Mobile menu overlay -->
    <div
        class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 z-30 mobile-overlay transition-opacity duration-300"
        :class="{ open: mobileMenuOpen }"
        @click="mobileMenuOpen = false"
    ></div>
</template>

<script>
import VNotification from "@/components/VNotification.vue";
import VCart from "./VCart.vue";
import VProfile from "@/components/VProfile.vue";
import VSearch from "./VSearch.vue";
import VCategories from "./VCategories.vue";
import VTheme from "@/components/VTheme.vue";

export default {
    components: {
        VNotification,
        VCart,
        VProfile,
        VSearch,
        VCategories,
        VTheme,
    },

    data() {
        return {
            searchQuery: "",
            mobileSearchOpen: false,
            mobileMenuOpen: false,
            notificationsOpen: false,
            userMenuOpen: false,
            categoriesOpen: false,
            mobileCategoriesOpen: false,
            searchFocused: false,
            sampleProducts: [],
            categories: [],
        };
    },
    computed: {
        featuredProducts() {
            return this.sampleProducts.slice(0, 3);
        },
        searchResults() {
            if (!this.searchQuery) return [];
            const query = this.searchQuery.toLowerCase();
            return this.sampleProducts
                .filter(
                    (p) =>
                        p.name.toLowerCase().includes(query) ||
                        p.category.toLowerCase().includes(query)
                )
                .slice(0, 5);
        },
    },

    methods: {
        handleSearch() {
            // Search is handled by the computed property
        },
        hideSearchResults() {
            setTimeout(() => {
                this.searchFocused = false;
            }, 200);
        },
        toggleNotifications() {
            this.notificationsOpen = !this.notificationsOpen;
            this.userMenuOpen = false;
            this.categoriesOpen = false;
        },
        closeNotifications() {
            setTimeout(() => {
                this.notificationsOpen = false;
            }, 200);
        },
        toggleUserMenu() {
            this.userMenuOpen = !this.userMenuOpen;
            this.notificationsOpen = false;
            this.categoriesOpen = false;
        },
        closeUserMenu() {
            setTimeout(() => {
                this.userMenuOpen = false;
            }, 200);
        },
        toggleCategories() {
            this.categoriesOpen = !this.categoriesOpen;
            this.notificationsOpen = false;
            this.userMenuOpen = false;
        },
        closeCategories() {
            setTimeout(() => {
                this.categoriesOpen = false;
            }, 200);
        },
        toggleMobileCategories() {
            this.mobileCategoriesOpen = !this.mobileCategoriesOpen;
        },
    },
};
</script>

<style>
/* Solo estilos esenciales para transiciones */
.dropdown-menu {
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.3s ease;
    pointer-events: none;
    display: none;
}
.dropdown-menu.open {
    opacity: 1;
    transform: translateY(0);
    pointer-events: all;
    display: block;
}
.mobile-menu {
    transform: translateX(-100%);
    transition: transform 0.3s ease;
    display: none;
}
.mobile-menu.open {
    transform: translateX(0);
    display: flex;
}
.mobile-overlay {
    transition: opacity 0.3s ease;
    display: none;
}
.mobile-overlay.open {
    display: block;
}
</style>
