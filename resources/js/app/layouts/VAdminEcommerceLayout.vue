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
    <q-layout view="hHh Lpr lFf" class="admin-layout bg-grey-1">
        <!-- Header Mejorado -->
        <q-header elevated class="admin-header bg-primary text-white">
            <q-toolbar class="q-px-md">
                <q-btn
                    flat
                    dense
                    round
                    icon="menu"
                    @click="toggleLeftDrawer"
                    class="q-mr-sm"
                />

                <q-toolbar-title class="admin-title">
                    <q-icon name="mdi-store" size="md" class="q-mr-sm" />
                    <span class="text-weight-bold">E-Commerce </span> Admin
                </q-toolbar-title>

                <q-space />

                <v-theme class="theme-toggle" />
                <v-notification />
                <v-profile class="profile-menu"></v-profile>
            </q-toolbar>
        </q-header>

        <!-- Sidebar Mejorado -->
        <q-drawer
            v-model="leftDrawerOpen"
            show-if-above
            bordered
            :width="280"
            class="admin-sidebar"
        >
            <q-scroll-area class="fit">
                <div class="sidebar-header q-pa-md">
                    <div class="text-h6 text-weight-bold">Admin Menu</div>
                    <div class="text-caption text-grey-7">Management Panel</div>
                </div>

                <q-separator />

                <q-list padding class="menu-list">
                    <!-- Dashboard -->
                    <q-item
                        clickable
                        v-ripple
                        :to="menu_dashboard.route"
                        @click="open(menu_dashboard)"
                        class="menu-item"
                        :active="isActive(menu_dashboard)"
                    >
                        <q-item-section avatar>
                            <q-icon color="primary" name="mdi-view-dashboard" />
                        </q-item-section>
                        <q-item-section>
                            {{ menu_dashboard.name }}
                        </q-item-section>
                    </q-item>

                    <!-- Products Section -->
                    <q-expansion-item
                        icon="mdi-package-variant"
                        label="Products"
                        default-opened
                        expand-icon-class="text-grey-6"
                        class="expansion-item"
                    >
                        <!-- Categories -->
                        <q-item
                            clickable
                            v-ripple
                            exact
                            :to="menu_category.route"
                            @click="open(menu_category)"
                            class="submenu-item"
                            :active="isActive(menu_category)"
                        >
                            <q-item-section avatar>
                                <q-icon
                                    color="primary"
                                    name="mdi-shape-outline"
                                    size="sm"
                                />
                            </q-item-section>
                            <q-item-section>
                                {{ menu_category.name }}
                            </q-item-section>
                        </q-item>

                        <!-- Products List -->
                        <q-item
                            clickable
                            v-ripple
                            exact
                            :to="menu_products.route"
                            @click="open(menu_products)"
                            class="submenu-item"
                            :active="isActive(menu_products)"
                        >
                            <q-item-section avatar>
                                <q-icon
                                    color="primary"
                                    name="mdi-format-list-bulleted"
                                    size="sm"
                                />
                            </q-item-section>
                            <q-item-section>
                                {{ menu_products.name }}
                            </q-item-section>
                        </q-item>
                    </q-expansion-item>

                    <!-- Orders Section -->
                    <q-expansion-item
                        icon="mdi-cart-outline"
                        label="Orders"
                        expand-icon-class="text-grey-6"
                        class="expansion-item"
                    >
                        <q-item
                            clickable
                            v-ripple
                            exact
                            to="/admin/orders"
                            class="submenu-item"
                        >
                            <q-item-section avatar>
                                <q-icon
                                    color="primary"
                                    name="mdi-format-list-checks"
                                    size="sm"
                                />
                            </q-item-section>
                            <q-item-section>All Orders</q-item-section>
                            <q-item-section side>
                                <q-badge color="blue" rounded>15</q-badge>
                            </q-item-section>
                        </q-item>

                        <q-item
                            clickable
                            v-ripple
                            exact
                            to="/admin/orders/pending"
                            class="submenu-item"
                        >
                            <q-item-section avatar>
                                <q-icon
                                    color="primary"
                                    name="mdi-clock-outline"
                                    size="sm"
                                />
                            </q-item-section>
                            <q-item-section>Pending</q-item-section>
                            <q-item-section side>
                                <q-badge color="orange" rounded>5</q-badge>
                            </q-item-section>
                        </q-item>
                    </q-expansion-item>

                    <!-- Customers -->
                    <q-item
                        clickable
                        v-ripple
                        exact
                        to="/admin/customers"
                        class="menu-item"
                    >
                        <q-item-section avatar>
                            <q-icon color="primary" name="mdi-account-group" />
                        </q-item-section>
                        <q-item-section>Customers</q-item-section>
                        <q-item-section side>
                            <q-badge color="green" rounded>1,245</q-badge>
                        </q-item-section>
                    </q-item>

                    <!-- Reports -->
                    <q-item
                        clickable
                        v-ripple
                        exact
                        to="/admin/reports"
                        class="menu-item"
                    >
                        <q-item-section avatar>
                            <q-icon color="primary" name="mdi-chart-bar" />
                        </q-item-section>
                        <q-item-section>Reports</q-item-section>
                    </q-item>
                </q-list>
            </q-scroll-area>
        </q-drawer>

        <!-- Main Content -->
        <q-page-container>
            <slot />
        </q-page-container>
    </q-layout>
</template>

<script>
export default {
    data() {
        return {
            menus: [],
            user: {},
            app_name: "",
            menu_category: {},
            menu_products: {},
            menu_dashboard: {},
            menu_models: {},
            menu_family: {},
            leftDrawerOpen: false,
        };
    },

    created() {
        this.user = this.$page.props.user;
        this.app_name = this.$page.props.app_name;
        this.menus = this.$page.props.ecommerce_menus;

        this.menu_category = this.menus.find((item) => item.id == "categories");
        this.menu_dashboard = this.menus.find((item) => item.id == "dashboard");
        this.menu_models = this.menus.find((item) => item.id == "models");
        this.menu_family = this.menus.find(
            (item) => item.id == "family_models"
        );
        this.menu_products = this.menus.find((item) => item.id == "products");
    },

    methods: {
        toggleLeftDrawer() {
            this.leftDrawerOpen = !this.leftDrawerOpen;
        },

        open(item) {
            window.location.href = item.route;
        },

        isActive(item) {
            //   return this.route.path === item.route;
        },
    },
};
</script>

<style lang="css" scoped>
/* Layout General */
.admin-layout {
    min-height: 100vh;
}

/* Header Styles */
.admin-header {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.admin-title {
    font-size: 1.25rem;
    display: flex;
    align-items: center;
}

/* Sidebar Styles */
.admin-sidebar {
    background: white;
    border-right: 1px solid rgba(0, 0, 0, 0.05);
}

.sidebar-header {
    background: rgba(0, 0, 0, 0.02);
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

/* Menu List Styles */
.menu-list {
    padding-top: 0;
}

.menu-item {
    margin: 4px 8px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.menu-item:hover {
    background: rgba(0, 0, 0, 0.03);
}

.menu-item.q-item--active {
    background: #e3f2fd;
    color: #1976d2;
    font-weight: 500;
}

.menu-item.q-item--active .q-icon {
    color: #1976d2;
}

/* Expansion Items */
.expansion-item .q-item {
    margin: 4px 8px;
    border-radius: 8px;
}

.expansion-item .q-item__section--avatar {
    min-width: 40px;
}

/* Submenu Items */
.submenu-item {
    padding-left: 56px !important;
    margin: 2px 8px;
    border-radius: 6px;
}

.submenu-item:hover {
    background: rgba(0, 0, 0, 0.03);
}

.submenu-item.q-item--active {
    background: rgba(25, 118, 210, 0.1);
    color: #1976d2;
}

.submenu-item.q-item--active .q-icon {
    color: #1976d2;
}

/* Notification Menu */
.notification-menu {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
}

.notification-header {
    background: #f5f5f5;
}

.notification-item {
    transition: all 0.2s ease;
}

.notification-item:hover {
    background: #f5f5f5;
}

.view-all {
    background: #f9f9f9;
}

/* Profile Menu */
.profile-menu {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
}

.profile-header {
    background: #f5f5f5;
    padding: 16px;
}

.menu-item {
    padding: 8px 16px;
}

/* Responsive Adjustments */
@media (max-width: 1023px) {
    .admin-sidebar {
        width: 240px !important;
    }
}

@media (max-width: 599px) {
    .admin-title span {
        display: none;
    }

    .admin-sidebar {
        width: 220px !important;
    }
}
</style>
