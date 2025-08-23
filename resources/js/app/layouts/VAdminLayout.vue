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
    <q-layout view="hHh Lpr lff" class="admin-layout" v-if="user.id">
        <!-- Enhanced Header -->
        <q-header elevated class="admin-header">
            <q-toolbar class="header-toolbar">
                <q-btn
                    dense
                    flat
                    round
                    icon="menu"
                    @click="toggleLeftDrawer"
                    class="menu-toggle"
                    aria-label="Toggle menu"
                />

                <q-toolbar-title class="app-title">
                    <q-avatar size="40px" class="app-logo">
                        <img src="../../../img/favicon.png" />
                    </q-avatar>
                    <span class="app-name">{{ app_name }}</span>
                    <q-badge color="primary" class="admin-badge">Admin</q-badge>
                </q-toolbar-title>

                <q-space />

                <div class="header-actions">
                    <v-theme class="theme-toggle" />
                    <v-profile class="profile-menu"></v-profile>
                </div>
            </q-toolbar>
        </q-header>

        <!-- Enhanced Sidebar -->
        <q-drawer
            show-if-above
            v-model="leftDrawerOpen"
            side="left"
            bordered
            class="admin-sidebar"
            :width="280"
        >
            <q-scroll-area class="sidebar-scroll">
                <q-list class="sidebar-menu">
                    <div v-for="(item, index) in menus" :key="index">
                        <q-item
                            clickable
                            v-ripple
                            @click="open(item)"
                            :active="isActive(item)"
                            class="menu-item"
                            :class="{ 'menu-item-active': isActive(item) }"
                        >
                            <q-item-section avatar>
                                <q-avatar
                                    size="md"
                                    color="primary"
                                    text-color="white"
                                    class="menu-icon"
                                    :icon="item.icon"
                                />
                            </q-item-section>

                            <q-item-section class="menu-text">
                                {{ item.name }}
                            </q-item-section>

                            <q-item-section side v-if="isActive(item)">
                                <q-icon
                                    name="mdi-chevron-right"
                                    color="primary"
                                    size="20px"
                                />
                            </q-item-section>
                        </q-item>
                        <q-separator class="menu-separator" />
                    </div>
                </q-list>

                <!-- Sidebar Footer -->
                <div class="sidebar-footer">
                    <div class="user-info">
                        <q-avatar
                            size="40px"
                            color="primary"
                            text-color="white"
                            class="q-mr-sm"
                        >
                            {{ getUserInitials(user.name) }}
                        </q-avatar>
                        <div class="user-details">
                            <div class="user-name text-weight-medium">
                                {{ user.name }}
                            </div>
                            <div class="user-role text-caption text-grey-6">
                                Administrator
                            </div>
                        </div>
                    </div>
                    <div class="text-caption text-center q-pa-sm text-grey-6">
                        &copy; {{ new Date().getFullYear() }} {{ $page.props.org_name }}
                    </div>
                </div>
            </q-scroll-area>
        </q-drawer>

        <!-- Main Content -->
        <q-page-container class="admin-content">
            <q-page :class="{ 'content-collapsed': !leftDrawerOpen }">
                <div class="content-wrapper">
                    <slot />
                </div>
            </q-page>
        </q-page-container>

        <!-- Loading State -->
        <q-page
            v-if="!user.id"
            class="loading-page fixed-full flex flex-center"
        >
            <div class="loading-content text-center">
                <q-spinner
                    size="3rem"
                    color="primary"
                    class="q-mb-md"
                    :thickness="3"
                />
                <p class="text-h6 text-grey-7 loading-text">
                    Initializing Admin Panel...
                </p>
                <div class="text-caption text-grey-5 q-mt-sm">
                    Please wait while we prepare your dashboard
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
        this.menus = this.$page.props.admin_routes;
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

        getUserInitials(name) {
            if (!name) return "A";
            return name
                .split(" ")
                .map((n) => n[0])
                .join("")
                .toUpperCase()
                .substring(0, 2);
        },
    },
};
</script>

<style lang="scss" scoped>
.admin-layout {
    background: #f8fafc;
}

.admin-header {
    background: linear-gradient(
        145deg,
        var(--q-primary) 0%,
        #3a7bd5 100%
    ) !important;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);

    .header-toolbar {
        padding: 0 16px;
        height: 64px;

        .menu-toggle {
            background: rgba(255, 255, 255, 0.15);
            transition: all 0.3s ease;

            &:hover {
                background: rgba(255, 255, 255, 0.25);
                transform: rotate(90deg);
            }
        }

        .app-title {
            display: flex;
            align-items: center;
            gap: 12px;

            .app-logo {
                border: 2px solid rgba(255, 255, 255, 0.3);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .app-name {
                font-size: 1.4rem;
                font-weight: 600;
                color: white;
            }

            .admin-badge {
                font-size: 0.7rem;
                padding: 4px 8px;
                border-radius: 12px;
            }
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }
    }
}

.admin-sidebar {
    background: white;
    box-shadow: 2px 0 20px rgba(0, 0, 0, 0.08);
    border-right: none !important;

    .sidebar-scroll {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .sidebar-menu {
        padding: 16px 0;
        flex: 1;

        .menu-item {
            padding: 12px 20px;
            margin: 4px 8px;
            border-radius: 10px;
            transition: all 0.3s ease;

            &:hover {
                background: rgba(0, 123, 255, 0.08);
                transform: translateX(4px);

                .menu-icon {
                    transform: scale(1.1);
                }
            }

            &.menu-item-active {
                background: rgba(0, 123, 255, 0.12);
                border-left: 4px solid var(--q-primary);

                .menu-text {
                    color: var(--q-primary);
                    font-weight: 600;
                }

                .menu-icon {
                    background: var(--q-primary) !important;
                }
            }

            .menu-icon {
                transition: transform 0.3s ease;
            }

            .menu-text {
                font-size: 0.95rem;
                font-weight: 500;
                color: #2d3748;
            }
        }

        .menu-separator {
            margin: 4px 16px;
            background: rgba(0, 0, 0, 0.06);
        }
    }

    .sidebar-footer {
        padding: 20px 16px;
        border-top: 1px solid rgba(0, 0, 0, 0.06);
        background: rgba(0, 0, 0, 0.02);

        .user-info {
            display: flex;
            align-items: center;

            .user-details {
                .user-name {
                    font-size: 0.9rem;
                    line-height: 1.2;
                }

                .user-role {
                    font-size: 0.75rem;
                }
            }
        }
    }
}

.admin-content {
    background: #f8fafc;

    .content-wrapper {
        padding: 24px;
    }

    .content-collapsed {
        margin-left: 0;
    }
}

.loading-page {
    background: white;
    z-index: 9999;

    .loading-content {
        .loading-text {
            animation: pulse 1.5s infinite;
        }
    }
}

@keyframes pulse {
    0% {
        opacity: 0.6;
    }
    50% {
        opacity: 1;
    }
    100% {
        opacity: 0.6;
    }
}

// Responsive adjustments
@media (max-width: 1023px) {
    .admin-header {
        .header-toolbar {
            padding: 0 12px;

            .app-title {
                .app-name {
                    font-size: 1.2rem;
                }
            }
        }
    }

    .admin-sidebar {
        width: 260px !important;
    }

    .admin-content {
        .content-wrapper {
            padding: 20px;
        }
    }
}

@media (max-width: 599px) {
    .admin-header {
        .header-toolbar {
            .app-title {
                .app-name {
                    font-size: 1.1rem;
                }

                .admin-badge {
                    display: none;
                }
            }
        }
    }

    .admin-sidebar {
        width: 240px !important;

        .menu-item {
            padding: 10px 16px !important;
        }
    }

    .admin-content {
        .content-wrapper {
            padding: 16px;
        }
    }
}

// Animation for sidebar
.admin-sidebar {
    transition: transform 0.3s ease;
}

// Smooth transitions for content
.admin-content {
    transition: margin-left 0.3s ease;
}
</style>
