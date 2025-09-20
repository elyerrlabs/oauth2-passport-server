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
    <v-user-layout>
        <q-page class="user-dashboard-page">
            <!-- Header Section -->
            <div class="welcome-section q-pa-lg">
                <div class="row items-center q-col-gutter-xl">
                    <div class="col-auto">
                        <div class="user-avatar-container">
                            <q-avatar
                                size="100px"
                                class="bg-white text-primary shadow-3"
                            >
                                {{ userInitials }}
                            </q-avatar>
                            <div class="online-indicator"></div>
                        </div>
                    </div>

                    <div class="col">
                        <div
                            class="text-h3 text-weight-bold text-white welcome-title"
                        >
                            {{ __("Welcome") }}, {{ user.name }}!
                        </div>
                        <div
                            class="text-subtitle1 welcome-subtitle q-mt-md text-blue-1"
                        >
                            {{
                                __(
                                    "Access and manage all your applications from one place"
                                )
                            }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content - Applications Grid -->
            <div class="dashboard-content q-px-lg q-pb-lg">
                <!-- Applications Header -->
                <div class="row items-center q-mb-lg">
                    <div class="col">
                        <div class="text-h5 text-weight-bold text-dark">
                            <q-icon
                                name="mdi-apps"
                                class="q-mr-sm text-primary"
                            />
                            {{ __("My applications") }}
                        </div>
                        <div class="text-caption text-grey-6">
                            {{
                                __(
                                    "All your connected applications in one place"
                                )
                            }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <q-input
                            v-model="searchTerm"
                            :placeholder="__('Search applications...')"
                            dense
                            outlined
                            clearable
                            class="search-input bg-white"
                            color="primary"
                        >
                            <template v-slot:append>
                                <q-icon name="mdi-magnify" color="primary" />
                            </template>
                        </q-input>
                    </div>
                </div>

                <!-- Applications Grid -->
                <div class="applications-grid">
                    <div
                        v-if="filteredApplications.length === 0"
                        class="empty-state text-center q-pa-xl"
                    >
                        <q-icon
                            name="mdi-application-outline"
                            size="64px"
                            color="grey-4"
                        />
                        <div class="text-h6 q-mt-md text-grey-6">
                            {{ __("No applications found") }}
                        </div>
                        <div class="text-body2 q-mt-sm text-grey-5">
                            {{
                                __(
                                    "Try adjusting your search or check back later"
                                )
                            }}
                        </div>
                    </div>

                    <div v-else class="row q-col-gutter-lg">
                        <div
                            v-for="(app, index) in filteredApplications"
                            :key="index"
                            class="col-12 col-sm-6 col-md-4 col-lg-3"
                        >
                            <q-card class="application-card shadow-3" bordered>
                                <q-card-section class="app-card-header">
                                    <div class="row items-center no-wrap">
                                        <div class="col">
                                            <div
                                                class="app-icon bg-primary shadow-2"
                                            >
                                                <q-icon
                                                    :name="
                                                        app.icon ||
                                                        'mdi-application'
                                                    "
                                                    size="24px"
                                                    color="white"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <q-btn
                                                flat
                                                round
                                                icon="mdi-open-in-new"
                                                size="sm"
                                                color="primary"
                                                @click="openApplication(app)"
                                            >
                                                <q-tooltip>{{
                                                    __("Open application")
                                                }}</q-tooltip>
                                            </q-btn>
                                        </div>
                                    </div>

                                    <div class="q-mt-md">
                                        <div
                                            class="text-h6 text-weight-medium app-name text-dark"
                                        >
                                            {{ app.name }}
                                        </div>
                                        <div
                                            class="text-caption text-grey-6 app-description q-mt-xs"
                                        >
                                            {{
                                                app.description ||
                                                __("No description available")
                                            }}
                                        </div>
                                    </div>
                                </q-card-section>

                                <q-separator />

                                <q-card-actions class="q-pa-md">
                                    <q-btn
                                        unelevated
                                        color="primary"
                                        icon="mdi-launch"
                                        :label="__('Open')"
                                        @click="openApplication(app)"
                                        class="full-width"
                                        no-caps
                                    />
                                </q-card-actions>
                            </q-card>
                        </div>
                    </div>
                </div>

                <!-- Settings & Preferences Section -->
                <div class="settings-section q-mt-xl">
                    <div class="row items-center q-mb-lg">
                        <div class="col">
                            <div class="text-h5 text-weight-bold text-dark">
                                <q-icon
                                    name="mdi-cog"
                                    class="q-mr-sm text-primary"
                                />
                                {{ __("Settings & Preferences") }}
                            </div>
                            <div class="text-caption text-grey-6">
                                {{
                                    __(
                                        "Manage your account settings and preferences"
                                    )
                                }}
                            </div>
                        </div>
                    </div>

                    <div class="row q-col-gutter-lg">
                        <div
                            v-for="(setting, index) in userSettings"
                            :key="'setting-' + index"
                            class="col-12 col-sm-6 col-md-4 col-lg-3"
                        >
                            <q-card class="setting-card shadow-3" bordered>
                                <q-card-section class="setting-card-header">
                                    <div class="row items-center no-wrap">
                                        <div class="col">
                                            <div
                                                class="setting-icon bg-primary shadow-2"
                                            >
                                                <q-icon
                                                    :name="
                                                        setting.icon ||
                                                        'mdi-cog'
                                                    "
                                                    size="24px"
                                                    color="white"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <q-btn
                                                flat
                                                round
                                                icon="mdi-chevron-right"
                                                size="sm"
                                                color="primary"
                                                @click="openSetting(setting)"
                                            >
                                                <q-tooltip>{{
                                                    __("Open setting")
                                                }}</q-tooltip>
                                            </q-btn>
                                        </div>
                                    </div>

                                    <div class="q-mt-md">
                                        <div
                                            class="text-h6 text-weight-medium setting-name text-dark"
                                        >
                                            {{ setting.name }}
                                        </div>
                                        <div
                                            class="text-caption text-grey-6 setting-description q-mt-xs"
                                        >
                                            {{
                                                setting.description ||
                                                __("Configure your preferences")
                                            }}
                                        </div>
                                    </div>
                                </q-card-section>

                                <q-separator />

                                <q-card-actions class="q-pa-md">
                                    <q-btn
                                        flat
                                        color="primary"
                                        icon="mdi-tune"
                                        :label="__('Configure')"
                                        @click="openSetting(setting)"
                                        class="full-width"
                                        no-caps
                                    />
                                </q-card-actions>
                            </q-card>
                        </div>
                    </div>
                </div>
            </div>
        </q-page>
    </v-user-layout>
</template>

<script>
export default {
    data() {
        return {
            refreshing: false,
            searchTerm: "",
        };
    },

    computed: {
        user() {
            return this.$page.props.user;
        },

        userInitials() {
            return `${this.user.name?.[0] || ""}${
                this.user.last_name?.[0] || ""
            }`.toUpperCase();
        },

        userRoutes() {
            return Object.values(this.$page.props.user_routes || {}).sort(
                (a, b) => a.name.localeCompare(b.name)
            );
        },

        userSettings() {
            return Object.values(this.$page.props.user_settings || {}).sort(
                (a, b) => a.name.localeCompare(b.name)
            );
        },

        filteredApplications() {
            if (!this.searchTerm) return this.userRoutes;

            const searchLower = this.searchTerm.toLowerCase();
            return this.userRoutes.filter(
                (app) =>
                    app.name.toLowerCase().includes(searchLower) ||
                    (app.description &&
                        app.description.toLowerCase().includes(searchLower))
            );
        },
    },

    methods: {
        openApplication(app) {
            if (app.route) {
                window.location.href = app.route;
            }
        },

        openSetting(setting) {
            if (setting.route) {
                window.location.href = setting.route;
            }
        },
    },
};
</script>

<style lang="scss" scoped>
.user-dashboard-page {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    min-height: 100vh;
}

.welcome-section {
    background: linear-gradient(145deg, var(--q-primary) 0%, #2563eb 100%);
    color: white;
    padding: 60px 32px;
    position: relative;
    overflow: hidden;

    &::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.1;
    }

    .user-avatar-container {
        position: relative;
        z-index: 1;

        .online-indicator {
            position: absolute;
            bottom: 8px;
            right: 8px;
            width: 20px;
            height: 20px;
            background: #22c55e;
            border: 3px solid white;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }
    }

    .welcome-title {
        font-size: 2.5rem;
        line-height: 1.2;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        z-index: 1;
        position: relative;
    }

    .welcome-subtitle {
        opacity: 0.9;
        max-width: 500px;
        z-index: 1;
        position: relative;
    }
}

.dashboard-content {
    padding-top: 40px;
    position: relative;
    z-index: 2;
}

.search-input {
    min-width: 280px;
    border-radius: 12px;

    :deep(.q-field__control) {
        border-radius: 12px;
    }
}

.applications-grid,
.settings-section {
    .application-card,
    .setting-card {
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e2e8f0;
        overflow: hidden;

        &:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
            border-color: var(--q-primary);
        }

        .app-card-header,
        .setting-card-header {
            padding: 24px;
        }

        .app-icon,
        .setting-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .app-name,
        .setting-name {
            font-size: 1.125rem;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .app-description,
        .setting-description {
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .q-card__actions {
            padding: 20px;

            .q-btn {
                border-radius: 12px;
                font-weight: 600;
                letter-spacing: 0.5px;
            }
        }
    }
}

.empty-state {
    background: white;
    border-radius: 16px;
    padding: 80px 40px;
    border: 2px dashed #e2e8f0;

    .q-icon {
        opacity: 0.3;
    }
}

// Responsive adjustments
@media (max-width: 1023px) {
    .welcome-section {
        padding: 40px 24px;

        .welcome-title {
            font-size: 2rem;
        }
    }

    .dashboard-content {
        padding-left: 24px;
        padding-right: 24px;
    }

    .search-input {
        min-width: 240px;
    }
}

@media (max-width: 767px) {
    .welcome-section {
        padding: 32px 20px;
        text-align: center;

        .welcome-title {
            font-size: 1.75rem;
        }

        .row.items-center {
            justify-content: center;
        }
    }

    .dashboard-content {
        padding-left: 16px;
        padding-right: 16px;
    }

    .search-input {
        min-width: 100%;
        margin-top: 20px;
    }

    .applications-grid,
    .settings-section {
        .row.q-col-gutter-lg {
            margin: 0 -8px;

            & > [class*="col-"] {
                padding: 8px;
            }
        }
    }
}

@media (max-width: 599px) {
    .welcome-section {
        padding: 24px 16px;

        .welcome-title {
            font-size: 1.5rem;
        }

        .user-avatar-container .q-avatar {
            width: 80px;
            height: 80px;
            font-size: 1.5rem;
        }

        .online-indicator {
            width: 16px;
            height: 16px;
            bottom: 6px;
            right: 6px;
        }
    }

    .application-card,
    .setting-card {
        .app-card-header,
        .setting-card-header {
            padding: 20px;
        }

        .app-icon,
        .setting-icon {
            width: 56px;
            height: 56px;
        }

        .app-name,
        .setting-name {
            font-size: 1rem;
        }
    }
}

// Dark mode support
.body--dark {
    .user-dashboard-page {
        background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
    }

    .application-card,
    .setting-card {
        background: #2d3748;
        border-color: #4a5568;

        .app-name,
        .setting-name {
            color: #e2e8f0;
        }

        .app-description,
        .setting-description {
            color: #a0aec0;
        }
    }

    .empty-state {
        background: #2d3748;
        border-color: #4a5568;
    }
}
</style>
