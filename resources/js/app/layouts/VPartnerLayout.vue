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
    <q-layout view="hHh lpR fFf">
        <q-header elevated class="header bg-primary text-white">
            <q-toolbar class="q-pr-sm">
                <q-btn
                    dense
                    flat
                    round
                    icon="menu"
                    @click="toggleLeftDrawer"
                    class="q-mr-sm"
                />

                <q-toolbar-title class="row items-center">
                    <q-avatar
                        size="40px"
                        class="bg-white text-primary q-mr-md"
                        icon="mdi-account-group"
                    />
                    <div>
                        <div class="text-h6 text-weight-bold">
                            Partner System
                        </div>
                        <div class="text-caption opacity-80">
                            Management Platform
                        </div>
                    </div>
                </q-toolbar-title>

                <div class="row items-center q-gutter-sm">
                    <v-theme />
                    <q-separator vertical spaced inset class="bg-white-20" />
                    <v-profile></v-profile>
                </div>
            </q-toolbar>
        </q-header>

        <q-drawer
            show-if-above
            v-model="leftDrawerOpen"
            side="left"
            bordered
            :width="280"
            class="drawer"
        >
            <q-scroll-area class="fit">
                <q-list padding class="menu-list">
                    <q-item-label header class="text-weight-bold text-grey-8">
                        Navigation
                    </q-item-label>

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
                                    size="24px"
                                    :color="
                                        isActive(item) ? 'primary' : 'grey-6'
                                    "
                                />
                            </q-item-section>

                            <q-item-section class="text-weight-medium">
                                {{ item.name }}
                            </q-item-section>

                            <q-item-section side v-if="isActive(item)">
                                <q-icon
                                    name="mdi-check"
                                    color="primary"
                                    size="16px"
                                />
                            </q-item-section>
                        </q-item>

                        <q-separator v-if="index < menus.length - 1" />
                    </div>
                </q-list>

                <div
                    class="absolute-bottom q-pa-md text-center text-caption text-grey-6"
                >
                   &copy; {{ new Date().getFullYear() }} {{ $page.props.org_name }}
                </div>
            </q-scroll-area>
        </q-drawer>

        <q-page-container>
            <div class="page-content">
                <slot />
            </div>
        </q-page-container>
    </q-layout>
</template>

<script>
export default {
    data() {
        return {
            leftDrawerOpen: true,
            app_name: "",
        };
    },

    computed: {
        menus() {
            return this.$page.props.partner_routes;
        },
    },

    created() {
        this.app_name = this.$page.props.app_name;
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
.header {
    background: linear-gradient(145deg, var(--q-primary), #1976d2);
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.15);
}

.drawer {
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.08);
}

.menu-list {
    padding-top: 16px;
    padding-bottom: 80px;
}

.menu-item {
    border-radius: 0 24px 24px 0;
    margin: 4px 8px;
    transition: all 0.3s ease;
}

.menu-item:hover {
    background-color: rgba(0, 0, 0, 0.04);
}

.active-menu-item {
    background: linear-gradient(45deg, #e3f2fd, #bbdefb);
    color: var(--q-primary);
    border-right: 4px solid var(--q-primary);
}

.page-content {
    max-width: 1400px;
    margin: 0 auto;
    width: 100%;
}

::v-deep .q-drawer__content {
    scrollbar-width: thin;
}

::v-deep .q-item__section--side {
    padding-right: 12px;
}

@media (max-width: 1024px) {
    .page-content {
        padding: 0 12px;
    }
}

@media (max-width: 600px) {
    .header .q-toolbar__title {
        font-size: 1.1rem;
    }

    .drawer {
        width: 250px !important;
    }
}

.q-drawer {
    transition: transform 0.3s ease, width 0.3s ease;
}

.menu-item {
    transition: background-color 0.2s ease, transform 0.2s ease;
}

.menu-item:active {
    transform: scale(0.98);
}

::v-deep .q-avatar {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.bg-white-20 {
    background-color: rgba(255, 255, 255, 0.2) !important;
}
</style>
