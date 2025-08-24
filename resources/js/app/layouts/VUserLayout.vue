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
    <q-layout view="hHh Lpr lff" class="modern-layout">
        <!-- Enhanced Header -->
        <q-header elevated class="modern-header bg-primary">
            <q-toolbar class="header-content">
                <q-btn
                    flat
                    dense
                    round
                    icon="menu"
                    class="toggle-sidebar"
                    @click="toggleMenu"
                />
                <q-toolbar-title class="app-title">
                    <div class="row items-center">
                        <q-avatar
                            size="32px"
                            class="q-mr-sm app-logo"
                            text-color="white"
                        >
                            <q-icon name="dashboard" size="18px" />
                        </q-avatar>
                        {{ app_name }}
                    </div>
                </q-toolbar-title>
                <q-space />

                <v-theme />
                <v-notification />
                <v-profile />
            </q-toolbar>
        </q-header>

        <!-- Enhanced Sidebar -->
        <q-drawer
            v-model="isSidebarOpen"
            show-if-above
            bordered
            class="modern-sidebar"
        >
            <q-scroll-area class="fit">
                <q-list class="sidebar-menu q-ma-sm">
                    <!-- User Profile Section -->
                    <q-item
                        class="user-profile-section"
                        v-if="$page.props.user"
                    >
                        <q-item-section avatar>
                            <q-avatar
                                size="48px"
                                color="primary"
                                text-color="white"
                            >
                                {{ userInitials }}
                            </q-avatar>
                        </q-item-section>

                        <q-item-section>
                            <q-item-label class="text-weight-bold user-name">{{
                                user.name
                            }}</q-item-label>
                            <q-item-label caption class="user-email">{{
                                user.email
                            }}</q-item-label>
                        </q-item-section>
                    </q-item>

                    <q-separator class="q-my-md" />

                    <!-- Account -->
                    <q-item-label header class="menu-section-title">
                        Account
                    </q-item-label>

                    <q-item
                        clickable
                        v-ripple
                        @click="open($page.props.user_dashboard)"
                        v-if="$page.props.user_dashboard.show"
                        class="modern-menu-item"
                    >
                        <q-item-section avatar>
                            <q-avatar
                                size="sm"
                                color="primary"
                                text-color="white"
                                :icon="$page.props.user_dashboard.icon"
                            />
                        </q-item-section>
                        <q-item-section>
                            {{ $page.props.user_dashboard.name }}
                        </q-item-section>
                    </q-item>

                    <!-- Developers -->
                    <template v-if="developers.show">
                        <q-separator class="q-my-md" />

                        <q-item-label header class="menu-section-title">
                            {{ developers.name }}
                        </q-item-label>

                        <q-item
                            clickable
                            class="modern-menu-item"
                            v-for="(item, index) in developers.menu"
                            :key="index"
                            @click="open(item)"
                        >
                            <q-item-section avatar>
                                <q-avatar
                                    size="sm"
                                    color="blue"
                                    text-color="white"
                                    :icon="item.icon"
                                />
                            </q-item-section>
                            <q-item-section>{{ item.name }}</q-item-section>
                        </q-item>
                    </template>

                    <!-- Dashboards -->
                    <q-separator class="q-my-md" />

                    <q-item-label header class="menu-section-title">
                        Dashboards
                    </q-item-label>

                    <q-item
                        clickable
                        v-ripple
                        @click="open(admin_dashboard)"
                        v-if="admin_dashboard.show"
                        class="modern-menu-item"
                    >
                        <q-item-section avatar>
                            <q-avatar
                                size="sm"
                                color="green"
                                text-color="white"
                                :icon="admin_dashboard.icon"
                            />
                        </q-item-section>
                        <q-item-section>
                            {{ admin_dashboard.name }}
                        </q-item-section>
                    </q-item>

                    <q-item
                        clickable
                        v-ripple
                        @click="open(transaction_dashboard)"
                        v-if="transaction_dashboard.show"
                        class="modern-menu-item"
                    >
                        <q-item-section avatar>
                            <q-avatar
                                size="sm"
                                color="orange"
                                text-color="white"
                                :icon="transaction_dashboard.icon"
                            />
                        </q-item-section>
                        <q-item-section>
                            {{ transaction_dashboard.name }}
                        </q-item-section>
                    </q-item>

                    <q-item
                        clickable
                        v-ripple
                        @click="open(partner_dashboard)"
                        v-if="partner_dashboard.show"
                        class="modern-menu-item"
                    >
                        <q-item-section avatar>
                            <q-avatar
                                size="sm"
                                color="purple"
                                text-color="white"
                                :icon="partner_dashboard.icon"
                            />
                        </q-item-section>
                        <q-item-section>
                            {{ partner_dashboard.name }}
                        </q-item-section>
                    </q-item>

                    <q-item
                        clickable
                        v-ripple
                        @click="open(ecommerce_dashboard)"
                        v-if="ecommerce_dashboard.show"
                        class="modern-menu-item"
                    >
                        <q-item-section avatar>
                            <q-avatar
                                size="sm"
                                color="amber"
                                text-color="white"
                                :icon="ecommerce_dashboard.icon"
                            />
                        </q-item-section>
                        <q-item-section>
                            {{ ecommerce_dashboard.name }}
                        </q-item-section>
                    </q-item>

                    <q-item
                        clickable
                        v-ripple
                        @click="open(settings)"
                        v-if="settings.show"
                        class="modern-menu-item"
                    >
                        <q-item-section avatar>
                            <q-avatar
                                size="sm"
                                color="grey"
                                text-color="white"
                                :icon="settings.icon"
                            />
                        </q-item-section>
                        <q-item-section>
                            {{ settings.name }}
                        </q-item-section>
                    </q-item>
                </q-list>
            </q-scroll-area>

            <!-- Sidebar Footer -->
            <div class="sidebar-footer">
                <q-list class="sidebar-menu q-ma-sm">
                    <q-item
                        clickable
                        v-ripple
                        @click="open($page.props.docs)"
                        v-if="$page.props.docs.show"
                        class="modern-menu-item"
                    >
                        <q-item-section avatar>
                            <q-avatar
                                size="sm"
                                color="grey"
                                text-color="white"
                                :icon="$page.props.docs.icon"
                            />
                        </q-item-section>
                        <q-item-section>
                            {{ $page.props.docs.name }}
                        </q-item-section>
                    </q-item>
                </q-list>
                <div class="text-caption text-center q-pa-sm text-grey-6">
                    &copy; {{ new Date().getFullYear() }}
                    {{ $page.props.org_name }}
                </div>
            </div>
        </q-drawer>

        <!-- Main Content -->
        <q-page-container>
            <q-page class="modern-page-content">
                <div class="page-container">
                    <slot />
                </div>
            </q-page>
        </q-page-container>
    </q-layout>
</template>

<script>
export default {
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
        this.user = this.$page.props.user;
        this.app_name = this.$page.props.app_name;
        this.menus = this.$page.props.user_routes;
        this.admin_dashboard = this.$page.props.admin_dashboard;
        this.transaction_dashboard = this.$page.props.transaction_dashboard;
        this.partner_dashboard = this.$page.props.partner_dashboard;
        this.settings = this.$page.props.settings;
        this.developers = this.$page.props.developers;
        this.ecommerce_dashboard = this.$page.props.ecommerce_dashboard;
    },

    mounted() {
        this.setupEventListeners();
    },

    methods: {
        open(item) {
            window.location.href = item.route;
        },

        toggleMenu() {
            this.isSidebarOpen = !this.isSidebarOpen;
        },

        handleResize() {
            if (window.innerWidth >= 992) {
                this.isSidebarOpen = false;
                document.body.style.overflow = "auto";
            }
        },

        setupEventListeners() {
            // Close sidebar when clicking on a menu item (mobile only)
            const menuItems = document.querySelectorAll(".modern-menu-item");
            menuItems.forEach((item) => {
                item.addEventListener("click", () => {
                    if (window.innerWidth < 992) {
                        this.toggleMenu();
                    }
                });
            });

            // Close menu when resizing to desktop mode
            window.addEventListener("resize", this.handleResize);
        },
    },

    beforeUnmount() {
        // Clean up event listeners
        window.removeEventListener("resize", this.handleResize);
    },
};
</script>

<style lang="scss" scoped>
// Modern layout styling
.modern-layout {
    // background: #f8fafc;
}

// Enhanced Header
.modern-header {
    background: linear-gradient(145deg, var(--q-primary), #3a7bd5) !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
    height: 64px;

    .header-content {
        padding: 0 20px;
    }

    .app-title {
        font-size: 1.4rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .app-logo {
        background: rgba(255, 255, 255, 0.2);
    }

    .toggle-sidebar {
        background: rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;

        &:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }
    }
}

// Enhanced Sidebar
.modern-sidebar {
    background: white;
    box-shadow: 2px 0 20px rgba(0, 0, 0, 0.08);
    border-right: none !important;

    .user-profile-section {
        padding: 20px 16px;

        .user-name {
            font-size: 1.1rem;
            color: #2d3748;
        }

        .user-email {
            font-size: 0.85rem;
            color: #718096;
        }
    }

    .menu-section-title {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #718096;
        padding: 0 20px;
        margin-bottom: 12px;
        font-weight: 700;
    }
}

// Modern Menu Items
.modern-menu-item {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    border-radius: 10px;
    margin: 4px 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    color: #2d3748;
    text-decoration: none;
    position: relative;

    &:hover {
        background-color: rgba(67, 97, 238, 0.08);
        transform: translateX(4px);
    }

    &.q-item--active {
        background-color: rgba(67, 97, 238, 0.12);
        color: var(--q-primary);
        font-weight: 500;

        .q-icon {
            color: var(--q-primary);
        }

        &::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background-color: var(--q-primary);
            border-radius: 0 10px 10px 0;
        }
    }
}

// Sidebar Footer
.sidebar-footer {
    border-top: 1px solid rgba(0, 0, 0, 0.06);
    background: rgba(0, 0, 0, 0.02);
}

// Main Content Area
.modern-page-content {
    //  background: #f8fafc;
    padding: 24px;
}

.page-container {
    //  background: white;
    border-radius: 12px;
    padding: 24px;
    //min-height: calc(100vh - 112px);
}

// Mobile Overlay
/*.mobile-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 899;
    transition: all 0.3s ease;
}*/

// Responsive Design
@media (max-width: 992px) {
    .modern-sidebar {
        width: 280px !important;
        transform: translateX(-100%);
    }

    .modern-sidebar.mobile-open {
        transform: translateX(0);
        box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
    }

    .modern-page-content {
        padding: 20px;
    }

    .page-container {
        padding: 20px;
        min-height: calc(100vh - 96px);
    }
}

@media (max-width: 576px) {
    .modern-header {
        padding: 0 15px;

        .app-title {
            font-size: 1.2rem;
        }
    }

    .modern-page-content {
        padding: 16px;
    }

    .page-container {
        padding: 16px;
    }
}
</style>
