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
                                color="white"
                                text-color="primary"
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
                            <q-icon name="mdi-application" class="q-mr-sm" />
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
                            class="search-input"
                        >
                            <template v-slot:append>
                                <q-icon name="mdi-magnify" />
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
                            <q-card class="application-card" flat bordered>
                                <q-card-section class="app-card-header">
                                    <div class="row items-center no-wrap">
                                        <div class="col">
                                            <div class="app-icon bg-blue-1">
                                                <q-icon
                                                    :name="app.icon"
                                                    size="24px"
                                                    :color="
                                                        app.iconColor
                                                            ? 'white'
                                                            : 'blue'
                                                    "
                                                />
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <q-btn
                                                flat
                                                round
                                                icon="mdi-open-in-new"
                                                size="sm"
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

                                <q-card-actions>
                                    <q-btn
                                        flat
                                        color="primary"
                                        icon="mdi-launch"
                                        :label="__('Open')"
                                        @click="openApplication(app)"
                                        class="full-width"
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
                                <q-icon name="mdi-cog" class="q-mr-sm" />
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
                            <q-card class="setting-card" flat bordered>
                                <q-card-section class="setting-card-header">
                                    <div class="row items-center no-wrap">
                                        <div class="col">
                                            <div
                                                class="setting-icon bg-primary"
                                            >
                                                <q-icon
                                                    :name="setting.icon"
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
                                                @click="openSetting(setting)"
                                                class="bg-primary text-white"
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

                                <q-card-actions>
                                    <q-btn
                                        flat
                                        icon="mdi-tune"
                                        :label="__('Configure')"
                                        @click="openSetting(setting)"
                                        class="full-width"
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
    padding: 40px 32px;

    .user-avatar-container {
        position: relative;

        .online-indicator {
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 16px;
            height: 16px;
            background: #22c55e;
            border: 2px solid white;
            border-radius: 50%;
        }
    }

    .welcome-title {
        font-size: 2.25rem;
        line-height: 1.2;
    }

    .welcome-subtitle {
        opacity: 0.9;
        max-width: 500px;
    }
}

.dashboard-content {
    padding-top: 24px;
}

.search-input {
    min-width: 250px;
}

.applications-grid {
    .application-card {
        border-radius: 12px;
        transition: all 0.3s ease;
        height: 100%;

        &:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .app-card-header {
            padding: 20px;
        }

        .app-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .app-name {
            font-size: 1.1rem;
            margin-bottom: 4px;
        }

        .app-description {
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    }
}

.settings-section {
    .setting-card {
        border-radius: 12px;
        transition: all 0.3s ease;
        height: 100%;

        &:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .setting-card-header {
            padding: 20px;
        }

        .setting-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .setting-name {
            font-size: 1.1rem;
            margin-bottom: 4px;
        }

        .setting-description {
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    }

    .advanced-settings-card {
        border-radius: 12px;

        .advanced-setting-item {
            border-radius: 8px;
            transition: background-color 0.2s ease;

            &:hover {
                background: rgba(0, 0, 0, 0.02);
            }
        }
    }
}

.empty-state {
    .q-icon {
        opacity: 0.5;
    }
}

// Responsive adjustments
@media (max-width: 1023px) {
    .welcome-section {
        padding: 32px 24px;

        .welcome-title {
            font-size: 1.875rem;
        }
    }

    .dashboard-content {
        padding-left: 24px;
        padding-right: 24px;
    }

    .search-input {
        min-width: 200px;
    }
}

@media (max-width: 599px) {
    .welcome-section {
        padding: 24px 16px;
        text-align: center;

        .welcome-title {
            font-size: 1.5rem;
        }
    }

    .dashboard-content {
        padding-left: 16px;
        padding-right: 16px;
    }

    .search-input {
        min-width: 100%;
        margin-top: 16px;
    }

    .application-card,
    .setting-card {
        .app-name,
        .setting-name {
            font-size: 1rem;
        }
    }
}
</style>
