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
    <q-layout view="hHh Lpr lff" v-if="user.id">
        <!-- Header -->
        <q-header elevated class="bg-primary text-white">
            <q-toolbar>
                <q-btn
                    dense
                    flat
                    round
                    icon="menu"
                    @click="toggleLeftDrawer"
                    class="q-mr-sm"
                />

                <q-toolbar-title class="row items-center">
                    <q-avatar size="md" class="q-mr-sm">
                        <img src="../../../img/favicon.png" />
                    </q-avatar>
                    <span class="text-weight-bold">{{ app_name }}</span>
                </q-toolbar-title>

                <div class="row items-center q-gutter-sm">
                    <v-theme />
                    <v-profile />
                </div>
            </q-toolbar>
        </q-header>

        <!-- Navigation Drawer -->
        <q-drawer
            show-if-above
            v-model="leftDrawerOpen"
            side="left"
            bordered
            :width="280"
            class="navigation-drawer"
        >
            <q-scroll-area class="fit">
                <q-list padding class="menu-list">
                    <div v-for="(item, index) in menus" :key="index">
                        <q-item
                            clickable
                            v-ripple
                            @click="open(item)"
                            :active="isActive(item)"
                            active-class="active-menu-item"
                            class="menu-item"
                        >
                            <q-item-section avatar>
                                <q-icon
                                    :name="item.icon"
                                    size="md"
                                    class="text-primary"
                                />
                            </q-item-section>

                            <q-item-section>
                                <div class="text-weight-medium">
                                    {{ item.name }}
                                </div>
                            </q-item-section>

                            <q-item-section side v-if="isActive(item)">
                                <q-icon
                                    name="mdi-circle-small"
                                    color="primary"
                                    size="md"
                                />
                            </q-item-section>
                        </q-item>
                        <q-separator />
                    </div>
                </q-list>

                <!-- Drawer Footer -->
                <div class="drawer-footer text-center q-pa-md">
                    <div class="text-caption text-grey-6">
                        {{ app_name }} Admin Panel
                    </div>
                    <div class="text-caption text-grey-5">
                        &copy; {{ new Date().getFullYear() }}
                        {{ $page.props.org_name }}
                    </div>
                </div>
            </q-scroll-area>
        </q-drawer>

        <!-- Main Content -->
        <q-page-container>
            <q-page
                :class="{
                    'page-content': true,
                    'drawer-open': leftDrawerOpen,
                    'drawer-closed': !leftDrawerOpen,
                }"
            >
                <div class="content-wrapper">
                    <slot />
                </div>
            </q-page>
        </q-page-container>

        <!-- Loading State -->
        <q-page v-if="!user.id" class="fixed-full flex flex-center bg-grey-1">
            <div class="text-center">
                <q-spinner-gears size="xl" color="primary" class="q-mb-md" />
                <div class="text-h6 text-grey-7">Initializing Admin Panel</div>
                <div class="text-caption text-grey-5 q-mt-sm">
                    Please wait while we load your dashboard...
                </div>
            </div>
        </q-page>
    </q-layout>
</template>

<script>
export default {
    data() {
        return {
            drawer: true,
            errors: {},
            leftDrawerOpen: true,
            menus: [],
            user: [],
            app_name: "",
        };
    },

    created() {
        this.user = this.$page.props.user;
        this.app_name = this.$page.props.app_name;
        this.menus = this.$page.props.transaction_routes;
    },

    methods: {
        toggleLeftDrawer() {
            this.leftDrawerOpen = !this.leftDrawerOpen;
        },

        open(item) {
            window.location.href = item.route;
        },

        isActive(item) {
            return item.route == window.location.href;
        },
    },
};
</script>

<style scoped>
.navigation-drawer {
    background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
    border-right: 1px solid #e0e0e0;
}

.menu-list {
    padding: 0;
}

.menu-item {
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
    margin: 2px 8px;
    border-radius: 8px;
}

.menu-item:hover {
    background-color: rgba(25, 118, 210, 0.08);
    transform: translateX(4px);
}

.active-menu-item {
    background-color: rgba(25, 118, 210, 0.12) !important;
    border-left: 3px solid #1976d2;
    border-radius: 8px 0 0 8px;
}

.active-menu-item .q-item__section--avatar .q-icon {
    color: #1976d2 !important;
}

.page-content {
    transition: all 0.3s ease;
    background-color: #f8f9fa;
}

.drawer-open {
    margin-left: 0;
}

.drawer-closed {
    margin-left: 0;
}

.content-wrapper {
    min-height: calc(100vh - 50px);
    padding: 20px;
}

.drawer-footer {
    border-top: 1px solid #e0e0e0;
    margin-top: auto;
}

/* Responsive adjustments */
@media (max-width: 1024px) {
    .navigation-drawer {
        width: 250px !important;
    }
}

@media (max-width: 600px) {
    .content-wrapper {
        padding: 16px;
    }

    .navigation-drawer {
        width: 100% !important;
        max-width: 280px;
    }
}

/* Smooth transitions */
.q-drawer {
    transition: transform 0.3s ease, width 0.3s ease;
}

.q-page {
    transition: margin-left 0.3s ease;
}

/* Loading animation */
.q-spinner-gears {
    animation: rotate 2s linear infinite;
}

@keyframes rotate {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Header enhancements */
.q-header {
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
}

.q-toolbar {
    min-height: 64px;
}

/* Scrollbar styling for drawer */
.q-scrollarea__thumb {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 4px;
}

.q-scrollarea__thumb:hover {
    background: rgba(0, 0, 0, 0.3);
}
</style>
