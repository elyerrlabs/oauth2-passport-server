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
                            <q-icon
                                name="mdi-account"
                                size="120px"
                                color="white"
                                class="user-avatar"
                            />
                            <div class="online-indicator"></div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="text-h3 text-weight-bold welcome-title">
                            Welcome {{ user.name }} {{ user.last_name }}
                        </div>
                        <div class="text-subtitle1 welcome-subtitle q-mt-md">
                            Hello, {{ user.name }}! We're glad you're here. What
                            would you like to do today? Below are several
                            options to manage your account and set it up as you
                            prefer.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="dashboard-content q-px-lg q-pb-lg">
                <div class="row q-col-gutter-lg">
                    <!-- Account Options Card -->
                    <div class="col-12 col-md-6">
                        <q-card
                            class="dashboard-card account-options-card"
                            flat
                            bordered
                        >
                            <q-card-section class="card-header">
                                <div
                                    class="text-h6 text-weight-medium card-title"
                                >
                                    <q-icon name="mdi-cog" class="q-mr-sm" />
                                    Account Options
                                </div>
                            </q-card-section>

                            <q-list class="option-list">
                                <q-item
                                    v-for="(item, index) in userRoutes"
                                    :key="index"
                                    clickable
                                    v-ripple
                                    @click="open(item)"
                                    class="option-item"
                                >
                                    <q-item-section avatar>
                                        <q-avatar
                                            size="md"
                                            color="primary"
                                            text-color="white"
                                            :icon="item.icon"
                                            class="option-icon"
                                        />
                                    </q-item-section>

                                    <q-item-section>
                                        <div class="option-name">
                                            {{ item.name }}
                                        </div>
                                    </q-item-section>

                                    <q-item-section side v-if="item.count">
                                        <q-badge
                                            color="primary"
                                            rounded
                                            class="option-badge"
                                        >
                                            {{ item.count }}
                                        </q-badge>
                                    </q-item-section>

                                    <q-item-section side>
                                        <q-icon
                                            name="mdi-chevron-right"
                                            color="grey-5"
                                        />
                                    </q-item-section>
                                </q-item>
                            </q-list>
                        </q-card>
                    </div>

                    <!-- Inspiration Card -->
                    <div class="col-12 col-md-6">
                        <q-card
                            class="dashboard-card inspiration-card"
                            flat
                            bordered
                        >
                            <q-card-section>
                                <div
                                    class="text-h6 text-weight-medium card-title"
                                >
                                    <q-icon
                                        name="mdi-lightbulb-on-outline"
                                        class="q-mr-sm"
                                    />
                                    Daily Inspiration
                                </div>
                                <div class="inspiration-quote q-mt-md">
                                    <q-icon
                                        name="mdi-format-quote-open"
                                        size="sm"
                                        color="primary"
                                        class="quote-icon"
                                    />
                                    <div class="quote-text">
                                        Great things are done by a series of
                                        small things brought together.
                                    </div>
                                    <div
                                        class="quote-author text-white q-mt-sm"
                                    >
                                        — Vincent Van Gogh
                                    </div>
                                </div>
                                <div class="text-caption text-white q-mt-lg">
                                    Stay productive while we improve your
                                    experience. More tools will appear here
                                    soon.
                                </div>
                            </q-card-section>
                        </q-card>

                        <!-- Upgrade Card -->
                        <q-card
                            class="dashboard-card upgrade-card q-mt-lg"
                            flat
                            bordered
                        >
                            <q-card-section>
                                <div
                                    class="text-h6 text-weight-medium card-title"
                                >
                                    <q-icon
                                        name="mdi-rocket-launch"
                                        class="q-mr-sm"
                                    />
                                    Dashboard Upgrade
                                </div>
                                <div class="text-body2 text-white q-mt-sm">
                                    We're working on personalized suggestions
                                    and insights. Soon, you'll be able to manage
                                    your apps and track your activity from this
                                    section.
                                </div>
                                <div class="upgrade-progress q-mt-md">
                                    <q-linear-progress
                                        rounded
                                        size="10px"
                                        :value="0.65"
                                        color="primary"
                                        class="q-mb-sm"
                                    />
                                    <div class="text-caption text-white">
                                        Development progress: 65%
                                    </div>
                                </div>
                            </q-card-section>
                        </q-card>
                    </div>
                </div>

                <!-- Coming Soon Features -->
                <q-card
                    class="dashboard-card features-card q-mt-lg"
                    flat
                    bordered
                >
                    <q-card-section>
                        <div class="text-h6 text-weight-medium card-title">
                            <q-icon name="mdi-tools" class="q-mr-sm" />
                            What's Coming Soon?
                        </div>

                        <div class="row q-col-gutter-md q-mt-md">
                            <div
                                v-for="(feature, index) in upcomingFeatures"
                                :key="index"
                                class="col-12 col-sm-6 col-md-4"
                            >
                                <div class="feature-item">
                                    <q-icon
                                        :name="feature.icon"
                                        size="24px"
                                        color="primary"
                                        class="q-mr-sm"
                                    />
                                    <span class="feature-name">{{
                                        feature.name
                                    }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="text-caption text-grey q-mt-lg">
                            We're building this for you. Stay tuned!
                        </div>
                    </q-card-section>
                </q-card>
            </div>
        </q-page>
    </v-user-layout>
</template>

<script>
export default {
    computed: {
        user() {
            return this.$page.props.user;
        },
        userRoutes() {
            return Object.values(this.$page.props.user_routes).sort((a, b) =>
                a.name.localeCompare(b.name)
            );
        },
        upcomingFeatures() {
            return [
                { icon: "mdi-chart-line", name: "App usage analytics" }, 
                { icon: "mdi-application-cog", name: "Application manager" },
                { icon: "mdi-code-tags", name: "Developer options" },
                { icon: "mdi-file-outline", name: "File manager" },
                { icon: "mdi-lock-check-outline", name: "Encrypt end-to-end" },
                { icon: "mdi-qrcode-scan", name: "TOP and QR Login" },
                { icon: "mdi-currency-btc", name: "Cryptocurrency payment" },
                {
                    icon: "mdi-dots-horizontal-circle-outline",
                    name: "And much more",
                },
            ];
        },
    },

    methods: {
        open(item) {
            window.location.href = item.route;
        },
    },
};
</script>

<style lang="scss" scoped>
.user-dashboard-page {
    background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
    min-height: 100vh;
}

.welcome-section {
    background: linear-gradient(145deg, var(--q-primary) 0%, #3a7bd5 100%);
    color: white;
    padding-top: 40px;
    padding-bottom: 60px;

    .user-avatar-container {
        position: relative;
        display: inline-block;

        .user-avatar {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            padding: 20px;
            backdrop-filter: blur(5px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .online-indicator {
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 20px;
            height: 20px;
            background: #4caf50;
            border: 2px solid white;
            border-radius: 50%;
        }
    }

    .welcome-title {
        font-size: 2.5rem;
        line-height: 1.2;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .welcome-subtitle {
        opacity: 0.9;
        max-width: 600px;
        line-height: 1.6;
    }
}

.dashboard-content {
    margin-top: -40px;
    position: relative;
    z-index: 1;
}

.dashboard-card {
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;

    &:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        background: rgba(0, 0, 0, 0.02);
    }

    .card-title {
        display: flex;
        align-items: center;
        color: var(--q-primary);
    }
}

.account-options-card {
    .option-list {
        padding: 8px 0;

        .option-item {
            padding: 16px 20px;
            border-radius: 8px;
            margin: 4px 8px;
            transition: background-color 0.2s ease;

            &:hover {
                background: rgba(0, 0, 0, 0.03);
            }

            .option-icon {
                transition: transform 0.2s ease;
            }

            &:hover .option-icon {
                transform: scale(1.1);
            }

            .option-name {
                font-weight: 500;
                color: #2d3748;
            }

            .option-badge {
                font-size: 12px;
                padding: 4px 8px;
            }
        }
    }
}

.inspiration-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;

    .card-title {
        color: white;
    }

    .inspiration-quote {
        position: relative;

        .quote-icon {
            position: absolute;
            top: -8px;
            left: -8px;
            opacity: 0.7;
        }

        .quote-text {
            font-size: 1.1rem;
            font-style: italic;
            line-height: 1.6;
            padding-left: 24px;
        }

        .quote-author {
            color: rgba(255, 255, 255, 0.8);
        }
    }
}

.upgrade-card {
    .upgrade-progress {
        .q-linear-progress {
            height: 8px;
        }
    }
}

.features-card {
    .feature-item {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        background: rgba(0, 0, 0, 0.02);
        border-radius: 8px;
        margin-bottom: 8px;
        transition: background-color 0.2s ease;

        &:hover {
            background: rgba(0, 0, 0, 0.04);
        }

        .feature-name {
            font-size: 0.95rem;
            color: #2d3748;
        }
    }
}

// Responsive adjustments
@media (max-width: 1023px) {
    .welcome-section {
        padding: 30px 20px 50px;

        .welcome-title {
            font-size: 2rem;
        }
    }

    .dashboard-content {
        padding-left: 20px;
        padding-right: 20px;
    }
}

@media (max-width: 599px) {
    .welcome-section {
        text-align: center;
        padding: 20px 16px 40px;

        .welcome-title {
            font-size: 1.75rem;
        }

        .user-avatar {
            padding: 16px;
            font-size: 80px;
        }
    }

    .dashboard-content {
        padding-left: 16px;
        padding-right: 16px;
    }

    .feature-item {
        padding: 10px 12px;
    }
}
</style>
