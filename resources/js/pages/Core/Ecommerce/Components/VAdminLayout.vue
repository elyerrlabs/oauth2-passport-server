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
    <v-layout>
        <template #aside>
            <!-- Dashboard -->
            <a
                @click="open(menu_dashboard)"
                class="flex items-center p-3 mx-2 my-1 rounded-lg cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200 dark:hover:border-gray-600"
                :class="{
                    'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400':
                        isActive(menu_dashboard),
                    'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700':
                        !isActive(menu_dashboard),
                }"
            >
                <i
                    class="fas fa-tachometer-alt w-6 text-blue-500 dark:text-blue-400 mr-3"
                ></i>
                <span class="font-medium">{{ __(menu_dashboard.name) }}</span>
            </a>

            <!-- Products Section -->
            <div class="expansion-item">
                <div
                    class="flex items-center justify-between p-3 mx-2 my-1 rounded-lg cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200 dark:hover:border-gray-600"
                    :class="{
                        'bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white':
                            expandedSections.products,
                        'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700':
                            !expandedSections.products,
                    }"
                    @click="toggleExpansion('products')"
                >
                    <div class="flex items-center">
                        <i
                            class="fas fa-box-open w-6 text-blue-500 dark:text-blue-400 mr-3"
                        ></i>
                        <span class="font-medium">{{ __("Products") }}</span>
                    </div>
                    <i
                        class="fas fa-chevron-down text-xs transition-transform duration-300"
                        :class="{
                            'rotate-180 text-blue-500 dark:text-blue-400':
                                expandedSections.products,
                            'text-gray-400 dark:text-gray-500':
                                !expandedSections.products,
                        }"
                    ></i>
                </div>

                <div
                    class="transition-all duration-300 overflow-hidden"
                    :class="expandedSections.products ? 'max-h-40' : 'max-h-0'"
                >
                    <a
                        @click="open(menu_category)"
                        class="flex items-center p-3 pl-11 my-1 rounded-lg cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200 dark:hover:border-gray-600"
                        :class="{
                            'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400':
                                isActive(menu_category),
                            'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700':
                                !isActive(menu_category),
                        }"
                    >
                        <i
                            class="fas fa-shapes w-5 text-blue-400 dark:text-blue-300 mr-3 text-sm"
                        ></i>
                        <span class="text-sm font-medium">
                            {{ __(menu_category.name) }}
                        </span>
                    </a>

                    <a
                        @click="open(menu_products)"
                        class="flex items-center p-3 pl-11 my-1 rounded-lg cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200 dark:hover:border-gray-600"
                        :class="{
                            'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400':
                                isActive(menu_products),
                            'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700':
                                !isActive(menu_products),
                        }"
                    >
                        <i
                            class="fas fa-list-alt w-5 text-blue-400 dark:text-blue-300 mr-3 text-sm"
                        ></i>
                        <span class="text-sm font-medium">
                            {{ __(menu_products.name) }}
                        </span>
                    </a>
                </div>
            </div>

            <!-- Orders Section -->
            <div class="expansion-item">
                <div
                    class="flex items-center justify-between p-3 mx-2 my-1 rounded-lg cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200 dark:hover:border-gray-600"
                    :class="{
                        'bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white':
                            expandedSections.orders,
                        'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700':
                            !expandedSections.orders,
                    }"
                    @click="toggleExpansion('orders')"
                >
                    <div class="flex items-center">
                        <i
                            class="fas fa-shopping-cart w-6 text-blue-500 dark:text-blue-400 mr-3"
                        ></i>
                        <span class="font-medium">{{ __("Orders") }}</span>
                    </div>
                    <i
                        class="fas fa-chevron-down text-xs transition-transform duration-300"
                        :class="{
                            'rotate-180 text-blue-500 dark:text-blue-400':
                                expandedSections.orders,
                            'text-gray-400 dark:text-gray-500':
                                !expandedSections.orders,
                        }"
                    ></i>
                </div>

                <div
                    class="transition-all duration-300 overflow-hidden"
                    :class="expandedSections.orders ? 'max-h-40' : 'max-h-0'"
                >
                    <a
                        @click="open(menu_orders)"
                        class="flex items-center p-3 pl-11 my-1 rounded-lg cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200 dark:hover:border-gray-600"
                        :class="{
                            'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400':
                                isActive(menu_orders),
                            'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700':
                                !isActive(menu_orders),
                        }"
                    >
                        <i
                            class="fas fa-receipt w-5 text-blue-400 dark:text-blue-300 mr-3 text-sm"
                        ></i>
                        <span class="text-sm font-medium">
                            {{ __(menu_orders.name) }}
                        </span>
                    </a>

                    <a
                        @click="open(menu_orders_pending)"
                        class="flex items-center p-3 pl-11 my-1 rounded-lg cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200 dark:hover:border-gray-600"
                        :class="{
                            'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400':
                                isActive(menu_orders_pending),
                            'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700':
                                !isActive(menu_orders_pending),
                        }"
                    >
                        <i
                            class="fas fa-clock w-5 text-blue-400 dark:text-blue-300 mr-3 text-sm"
                        ></i>
                        <span class="text-sm font-medium">
                            {{ menu_orders_pending.name }}
                        </span>
                    </a>
                </div>
            </div>

            <!-- Customers -->
            <a
                @click="open(menu_orders_customer)"
                class="flex items-center p-3 mx-2 my-1 rounded-lg cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200 dark:hover:border-gray-600"
                :class="{
                    'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400':
                        isActive(menu_orders_customer),
                    'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700':
                        !isActive(menu_orders_customer),
                }"
            >
                <i
                    class="fas fa-users w-6 text-blue-500 dark:text-blue-400 mr-3"
                ></i>
                <span class="font-medium">{{
                    __(menu_orders_customer.name)
                }}</span>
            </a>
        </template>
        <template #main>
            <slot />
        </template>
    </v-layout>
</template>

<script>
import VNotification from "@/components/VNotification.vue";
import VProfile from "@/components/VProfile.vue";
import VLayout from "@/components/VLayout.vue";
import { router } from "@inertiajs/vue3";

export default {
    components: {
        VNotification,
        VProfile,
        VLayout,
    },

    data() {
        return {
            menus: [],
            user: {},
            app_name: "",
            menu_category: {},
            menu_products: {},
            menu_dashboard: {},
            menu_orders: {},
            menu_orders_pending: {},
            menu_orders_customer: {},
            leftDrawerOpen: true,
            expandedSections: {
                products: true,
                orders: true,
            },
        };
    },

    created() {
        this.user = this.$page.props.user || {};
        this.app_name = this.$page.props.app_name || "Admin";
        this.menus = this.$page.props.ecommerce_menus || [];

        // Safe menu finding with fallbacks
        this.menu_category = this.menus.find(
            (item) => item?.id == "categories"
        ) || { name: "Categories", route: "#" };
        this.menu_dashboard = this.menus.find(
            (item) => item?.id == "dashboard"
        ) || { name: "Dashboard", route: "#" };
        this.menu_products = this.menus.find(
            (item) => item?.id == "products"
        ) || { name: "Products", route: "#" };
        this.menu_orders = this.menus.find((item) => item?.id == "orders") || {
            name: "Orders",
            route: "#",
            icon: "cart",
        };
        this.menu_orders_pending = this.menus.find(
            (item) => item?.id == "orders_pending"
        ) || { name: "Pending Orders", route: "#", icon: "clock" };
        this.menu_orders_customer = this.menus.find(
            (item) => item?.id == "orders_customers"
        ) || { name: "Customers", route: "#", icon: "users" };
    },

    methods: {
        toggleLeftDrawer() {
            this.leftDrawerOpen = !this.leftDrawerOpen;
        },

        toggleExpansion(section) {
            this.expandedSections[section] = !this.expandedSections[section];
        },

        open(item) {
            if (item && item.route && item.route !== "#") {
                router.visit(item.route);
            }
        },

        isActive(item) {
            if (!item || !item.route || item.route === "#") return false;

            const currentPath = window.location.pathname;
            const itemPath = new URL(item.route, window.location.origin)
                .pathname;

            return (
                currentPath === itemPath ||
                currentPath.startsWith(itemPath + "/")
            );
        },
    },
};
</script>
