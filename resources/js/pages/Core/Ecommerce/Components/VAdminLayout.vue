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
    <div class="admin-layout min-h-screen bg-gray-100 flex">
        <!-- Header -->
        <header class="bg-blue-600 text-gray-600 shadow-md fixed w-full z-30">
            <div class="flex items-center justify-between px-4 py-2">
                <div class="flex items-center text-white">
                    <button
                        class="p-2 rounded-full hover:bg-blue-700 mr-2 focus:outline-none"
                        @click="toggleLeftDrawer"
                    >
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="text-white flex items-center text-xl">
                        <i class="fas fa-store mr-2"></i>
                        <span class="font-bold">{{ __("eCommerce") }} </span>
                    </div>
                </div>

                <div class="flex items-center xs:space-x-2 md:space-x-4">
                    <!-- Notifications -->
                    <v-notification />

                    <!-- User menu (hidden on mobile) -->
                    <v-profile />
                </div>
            </div>
        </header>

        <!-- Sidebar -->
        <aside
            class="admin-sidebar fixed left-0 top-0 h-full bg-white border-r border-gray-200 z-20 transition-transform duration-300 pt-14"
            :class="{
                'translate-x-0': leftDrawerOpen,
                '-translate-x-full': !leftDrawerOpen,
            }"
            style="width: 280px"
        >
            <div class="h-full overflow-y-auto">
                <div class="sidebar-header p-4 border-b border-gray-200">
                    <div class="text-xl font-bold">{{ __("Admin Menu") }}</div>
                    <div class="text-sm text-gray-500">
                        {{ __("Management Panel") }}
                    </div>
                </div>

                <nav class="menu-list py-4">
                    <!-- Dashboard -->
                    <a
                        @click="open(menu_dashboard)"
                        :class="{
                            'bg-blue-50 text-blue-600':
                                isActive(menu_dashboard),
                            'text-gray-700 hover:bg-gray-100':
                                !isActive(menu_dashboard),
                        }"
                        class="flex items-center p-3 mx-2 my-1 rounded-lg cursor-pointer transition-colors duration-200"
                    >
                        <i
                            class="fas fa-tachometer-alt w-6 text-blue-500 mr-3"
                        ></i>
                        <span>{{ __(menu_dashboard.name) }}</span>
                    </a>

                    <!-- Products Section -->
                    <div class="expansion-item">
                        <div
                            class="flex items-center justify-between p-3 mx-2 my-1 rounded-lg cursor-pointer hover:bg-gray-100"
                            @click="toggleExpansion('products')"
                        >
                            <div class="flex items-center">
                                <i
                                    class="fas fa-box-open w-6 text-blue-500 mr-3"
                                ></i>
                                <span>{{ __("Products") }}</span>
                            </div>
                            <i
                                class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-300"
                                :class="{
                                    'rotate-180': expandedSections.products,
                                }"
                            ></i>
                        </div>

                        <div
                            class="submenu transition-all duration-300 overflow-hidden"
                            :class="
                                expandedSections.products
                                    ? 'max-h-40'
                                    : 'max-h-0'
                            "
                        >
                            <a
                                @click="open(menu_category)"
                                :class="{
                                    'bg-blue-50 text-blue-600':
                                        isActive(menu_category),
                                    'text-gray-700 hover:bg-gray-100':
                                        !isActive(menu_category),
                                }"
                                class="flex items-center p-3 pl-11 my-1 rounded-lg cursor-pointer transition-colors duration-200"
                            >
                                <i
                                    class="fas fa-shapes w-5 text-blue-400 mr-3 text-sm"
                                ></i>
                                <span class="text-sm">
                                    {{ __(menu_category.name) }}
                                </span>
                            </a>

                            <a
                                @click="open(menu_products)"
                                :class="{
                                    'bg-blue-50 text-blue-600':
                                        isActive(menu_products),
                                    'text-gray-700 hover:bg-gray-100':
                                        !isActive(menu_products),
                                }"
                                class="flex items-center p-3 pl-11 my-1 rounded-lg cursor-pointer transition-colors duration-200"
                            >
                                <i
                                    class="fas fa-list-alt w-5 text-blue-400 mr-3 text-sm"
                                ></i>
                                <span class="text-sm">
                                    {{ __(menu_products.name) }}
                                </span>
                            </a>
                        </div>
                    </div>

                    <!-- Orders Section -->
                    <div class="expansion-item">
                        <div
                            class="flex items-center justify-between p-3 mx-2 my-1 rounded-lg cursor-pointer hover:bg-gray-100"
                            @click="toggleExpansion('orders')"
                        >
                            <div class="flex items-center">
                                <i
                                    class="fas fa-shopping-cart w-6 text-blue-500 mr-3"
                                ></i>
                                <span>{{ __("Orders") }}</span>
                            </div>
                            <i
                                class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-300"
                                :class="{
                                    'rotate-180': expandedSections.orders,
                                }"
                            ></i>
                        </div>

                        <div
                            class="submenu transition-all duration-300 overflow-hidden"
                            :class="
                                expandedSections.orders ? 'max-h-40' : 'max-h-0'
                            "
                        >
                            <a
                                @click="open(menu_orders)"
                                :class="{
                                    'bg-blue-50 text-blue-600':
                                        isActive(menu_orders),
                                    'text-gray-700 hover:bg-gray-100':
                                        !isActive(menu_orders),
                                }"
                                class="flex items-center p-3 pl-11 my-1 rounded-lg cursor-pointer transition-colors duration-200"
                            >
                                <i
                                    :class="[
                                        'mdi',
                                        menu_orders.icon,
                                        'w-5 text-blue-400 mr-3 text-sm',
                                    ]"
                                ></i>
                                <span class="text-sm">
                                    {{ __(menu_orders.name) }}
                                </span>
                            </a>

                            <a
                                @click="open(menu_orders_pending)"
                                :class="{
                                    'bg-blue-50 text-blue-600':
                                        isActive(menu_orders_pending),
                                    'text-gray-700 hover:bg-gray-100':
                                        !isActive(menu_orders_pending),
                                }"
                                class="flex items-center p-3 pl-11 my-1 rounded-lg cursor-pointer transition-colors duration-200"
                            >
                                <i
                                    :class="[
                                        'mdi',
                                        menu_orders_pending.icon,
                                        'w-5 text-blue-400 mr-3 text-sm',
                                    ]"
                                ></i>
                                <span class="text-sm">
                                    {{ menu_orders_pending.name }}
                                </span>
                            </a>
                        </div>
                    </div>

                    <!-- Customers -->
                    <a
                        @click="open(menu_orders_customer)"
                        :class="{
                            'bg-blue-50 text-blue-600':
                                isActive(menu_orders_customer),
                            'text-gray-700 hover:bg-gray-100':
                                !isActive(menu_orders_customer),
                        }"
                        class="flex items-center p-3 mx-2 my-1 rounded-lg cursor-pointer transition-colors duration-200"
                    >
                        <i
                            :class="[
                                'mdi',
                                menu_orders_customer.icon,
                                'w-6 text-blue-500 mr-3',
                            ]"
                        ></i>
                        <span>{{ __(menu_orders_customer.name) }}</span>
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main
            class="main-content flex-1 transition-all duration-300 pt-14"
            :class="{ 'md:ml-72': leftDrawerOpen }"
        >
            <div class="p-2">
                <slot />
            </div>
        </main>

        <!-- Mobile overlay -->
        <div
            v-if="leftDrawerOpen"
            @click="leftDrawerOpen = false"
            class="fixed inset-0 bg-gray-900 bg-opacity-50 z-10 md:hidden"
        ></div>
    </div>
</template>

<script>
import VNotification from "./VNotification.vue";
import VProfile from "./VProfile.vue";

export default {
    components: {
        VNotification,
        VProfile,
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
        this.user = this.$page.props.user;
        this.app_name = this.$page.props.app_name;
        this.menus = this.$page.props.ecommerce_menus;

        this.menu_category = this.menus.find((item) => item.id == "categories");
        this.menu_dashboard = this.menus.find((item) => item.id == "dashboard");
        this.menu_products = this.menus.find((item) => item.id == "products");
        this.menu_orders = this.menus.find((item) => item.id == "orders");
        this.menu_orders_pending = this.menus.find(
            (item) => item.id == "orders_pending"
        );

        this.menu_orders_customer = this.menus.find(
            (item) => item.id == "orders_customers"
        );
    },

    methods: {
        toggleLeftDrawer() {
            this.leftDrawerOpen = !this.leftDrawerOpen;
        },

        toggleExpansion(section) {
            this.expandedSections[section] = !this.expandedSections[section];
        },

        open(item) {
            window.location.href = item.route;
        },

        isActive(item) {
            return window.location.href === item.route;
        },
    },
};
</script>
