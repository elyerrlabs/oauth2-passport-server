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
    <v-guest-layout>
        <template #body>
            <q-page class="ca-landing q-pa-md">
                <!-- Top: Title + quick links -->
                <div class="row items-center justify-between q-mb-lg">
                    <div>
                        <div class="text-h4 text-weight-bold text-white">
                            OAuth2 Passport Server
                        </div>
                        <div class="text-grey-4">
                            OAuth2 + OpenID Connect • Modular core •
                            Elymod-friendly
                        </div>
                    </div>
                    <div class="row q-gutter-sm gt-sm">
                        <q-btn
                            outline
                            color="white"
                            icon="mdi-book-open-page-variant"
                            label="Docs"
                            @click="go('/documentation')"
                        />
                        <q-btn
                            outline
                            color="white"
                            icon="mdi-api"
                            label="API"
                            @click="go('/oauth/api-keys')"
                        />
                        <q-btn
                            outline
                            color="white"
                            icon="mdi-shield-key"
                            label="OAuth2 Keys"
                            @click="go('/oauth/clients')"
                        />
                    </div>
                </div>

                <!-- Mobile buttons -->
                <div class="row q-gutter-sm justify-center lt-md q-mb-md">
                    <q-btn
                        outline
                        color="white"
                        icon="mdi-book-open-page-variant"
                        @click="go('/docs')"
                    />
                    <q-btn
                        outline
                        color="white"
                        icon="mdi-api"
                        @click="go('/api')"
                    />
                    <q-btn
                        outline
                        color="white"
                        icon="mdi-shield-key"
                        @click="go('/admin/oauth/clients')"
                    />
                </div>

                <!-- Orbit: Auth Server center + orbiting apps -->
                <div class="orbital-container">
                    <div class="orbital-system">
                        <!-- Orbit paths -->
                        <div class="orbit orbit-1"></div>
                        <div class="orbit orbit-2"></div>
                        <div class="orbit orbit-3"></div>

                        <!-- Central Server -->
                        <div class="central-server">
                            <i class="mdi mdi-server server-icon"></i>
                            <h3 class="server-title">OAuth2 Server</h3>
                            <p class="server-subtitle">Central Core</p>
                        </div>

                        <!-- Orbiting Applications -->
                        <div class="app-node node-1">
                            <i class="mdi mdi-cloud-outline app-icon"></i>
                            <p class="app-title">Cloud</p>
                        </div>

                        <div class="app-node node-2">
                            <i class="mdi mdi-security-network app-icon"></i>
                            <p class="app-title">VPN</p>
                        </div>

                        <div class="app-node node-3">
                            <i class="mdi mdi-shopping app-icon"></i>
                            <p class="app-title">E-commerce</p>
                        </div>

                        <div class="app-node node-4">
                            <i class="mdi mdi-cellphone-lock app-icon"></i>
                            <p class="app-title">Application X</p>
                        </div>

                        <div class="app-node node-5">
                            <i class="mdi mdi-database app-icon"></i>
                            <p class="app-title">Microservice A</p>
                        </div>

                        <div class="app-node node-6 q-pa-sm">
                            <i class="mdi mdi-code-braces app-icon"></i>
                            <p class="app-title">Microservice B</p>
                        </div>

                        <div class="app-node node-7">
                            <i class="mdi mdi-cog app-icon"></i>
                            <p class="app-title">Módulo Elymod</p>
                        </div>

                        <div class="app-node node-8">
                            <i class="mdi mdi-connection app-icon"></i>
                            <p class="app-title">OpenID Connect</p>
                        </div>
                    </div>
                </div>

                <!-- Descriptive text under orbit -->
                <div class="q-mb-lg text-center q-pa-sm">
                    <div class="text-h6 text-weight-bold text-white q-mb-sm">
                        A modular, secure, and centralized server
                    </div>
                    <div class="text-grey-4">
                        Our server supports both <b>core modules</b> and
                        <b>isolated Elymod modules</b>, allowing third-party
                        applications to be integrated without compromising
                        system stability. Each module manages its own
                        dependencies and does not affect the core in case of
                        failure. In addition, it can connect to any external
                        application or microservice to
                        <b
                            >centralize authentication, control, and advanced
                            permissions</b
                        >.
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row q-col-gutter-md q-mb-lg">
                    <!-- Applications -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <q-card class="panel glass" flat bordered>
                            <q-card-section class="row items-center">
                                <q-icon
                                    name="mdi-apps"
                                    size="26px"
                                    class="q-mr-sm"
                                />
                                <div class="text-subtitle2 text-weight-medium">
                                    Applications
                                </div>
                            </q-card-section>
                            <q-separator dark />
                            <q-list dense>
                                <q-item
                                    clickable
                                    v-ripple
                                    @click="go('/admin/apps')"
                                >
                                    <q-item-section avatar
                                        ><q-icon name="mdi-plus-box"
                                    /></q-item-section>
                                    <q-item-section
                                        >Register Application
                                        (OIDC/OAuth2)</q-item-section
                                    >
                                </q-item>
                                <q-item
                                    clickable
                                    v-ripple
                                    @click="go('/admin/clients')"
                                >
                                    <q-item-section avatar
                                        ><q-icon name="mdi-account-key"
                                    /></q-item-section>
                                    <q-item-section
                                        >Manage OAuth2 Clients &
                                        Secrets</q-item-section
                                    >
                                </q-item>
                                <q-item
                                    clickable
                                    v-ripple
                                    @click="go('/admin/scopes')"
                                >
                                    <q-item-section avatar
                                        ><q-icon name="mdi-text-recognition"
                                    /></q-item-section>
                                    <q-item-section
                                        >Scopes & Claims</q-item-section
                                    >
                                </q-item>
                            </q-list>
                        </q-card>
                    </div>

                    <!-- Access Control -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <q-card class="panel glass" flat bordered>
                            <q-card-section class="row items-center">
                                <q-icon
                                    name="mdi-shield-account"
                                    size="26px"
                                    class="q-mr-sm"
                                />
                                <div class="text-subtitle2 text-weight-medium">
                                    Access Control
                                </div>
                            </q-card-section>
                            <q-separator dark />
                            <q-list dense>
                                <q-item
                                    clickable
                                    v-ripple
                                    @click="go('/admin/groups')"
                                >
                                    <q-item-section avatar
                                        ><q-icon
                                            name="mdi-account-group-outline"
                                    /></q-item-section>
                                    <q-item-section
                                        >Groups & Services</q-item-section
                                    >
                                </q-item>
                                <q-item
                                    clickable
                                    v-ripple
                                    @click="go('/admin/roles')"
                                >
                                    <q-item-section avatar
                                        ><q-icon name="mdi-shield-star-outline"
                                    /></q-item-section>
                                    <q-item-section
                                        >Service Roles
                                        (non-traditional)</q-item-section
                                    >
                                </q-item>
                                <q-item
                                    clickable
                                    v-ripple
                                    @click="go('/admin/policies')"
                                >
                                    <q-item-section avatar
                                        ><q-icon name="mdi-script-text-outline"
                                    /></q-item-section>
                                    <q-item-section
                                        >Policy Rules</q-item-section
                                    >
                                </q-item>
                            </q-list>
                        </q-card>
                    </div>

                    <!-- Integrations -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <q-card class="panel glass" flat bordered>
                            <q-card-section class="row items-center">
                                <q-icon
                                    name="mdi-connection"
                                    size="26px"
                                    class="q-mr-sm"
                                />
                                <div class="text-subtitle2 text-weight-medium">
                                    Integrations
                                </div>
                            </q-card-section>
                            <q-separator dark />
                            <q-list dense>
                                <q-item
                                    clickable
                                    v-ripple
                                    @click="go('/cloud')"
                                >
                                    <q-item-section avatar
                                        ><q-icon name="mdi-cloud-outline"
                                    /></q-item-section>
                                    <q-item-section>Cloud</q-item-section>
                                    <q-item-section side
                                        ><q-icon name="mdi-arrow-right"
                                    /></q-item-section>
                                </q-item>
                                <q-item clickable v-ripple @click="go('/vpn')">
                                    <q-item-section avatar
                                        ><q-icon name="mdi-shield-lock"
                                    /></q-item-section>
                                    <q-item-section>VPN</q-item-section>
                                    <q-item-section side
                                        ><q-icon name="mdi-arrow-right"
                                    /></q-item-section>
                                </q-item>
                                <q-item
                                    clickable
                                    v-ripple
                                    @click="go('/ecommerce')"
                                >
                                    <q-item-section avatar
                                        ><q-icon name="mdi-cart-outline"
                                    /></q-item-section>
                                    <q-item-section>eCommerce</q-item-section>
                                    <q-item-section side
                                        ><q-icon name="mdi-arrow-right"
                                    /></q-item-section>
                                </q-item>
                                <q-item clickable v-ripple @click="go('/x')">
                                    <q-item-section avatar
                                        ><q-icon name="mdi-application"
                                    /></q-item-section>
                                    <q-item-section>App X</q-item-section>
                                    <q-item-section side
                                        ><q-icon name="mdi-arrow-right"
                                    /></q-item-section>
                                </q-item>
                            </q-list>
                        </q-card>
                    </div>

                    <!-- Observability -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <q-card class="panel glass" flat bordered>
                            <q-card-section class="row items-center">
                                <q-icon
                                    name="mdi-text-box-search-outline"
                                    size="26px"
                                    class="q-mr-sm"
                                />
                                <div class="text-subtitle2 text-weight-medium">
                                    Observability
                                </div>
                            </q-card-section>
                            <q-separator dark />
                            <q-list dense>
                                <q-item
                                    clickable
                                    v-ripple
                                    @click="go('/admin/audit')"
                                >
                                    <q-item-section avatar
                                        ><q-icon
                                            name="mdi-clipboard-text-outline"
                                    /></q-item-section>
                                    <q-item-section>Audit Logs</q-item-section>
                                </q-item>
                                <q-item
                                    clickable
                                    v-ripple
                                    @click="go('/admin/tokens')"
                                >
                                    <q-item-section avatar
                                        ><q-icon name="mdi-key-variant"
                                    /></q-item-section>
                                    <q-item-section
                                        >Issued Tokens</q-item-section
                                    >
                                </q-item>
                                <q-item
                                    clickable
                                    v-ripple
                                    @click="go('/admin/status')"
                                >
                                    <q-item-section avatar
                                        ><q-icon name="mdi-heart-pulse"
                                    /></q-item-section>
                                    <q-item-section
                                        >Service Health</q-item-section
                                    >
                                </q-item>
                            </q-list>
                        </q-card>
                    </div>
                </div>

                <!-- Extra info section -->
                <div class="q-mb-lg q-pa-md glass text-center">
                    <div class="text-h6 text-weight-bold q-mb-sm">
                        Why OAuth2 Passport Server?
                    </div>
                    <div class="text-grey-4">
                        • Advanced permissions based on
                        <b>applications and groups</b>, going beyond the
                        traditional role model.<br />
                        • Seamless integration with
                        <b
                            >Cloud, VPN, eCommerce, and any external
                            microservice</b
                        >.<br />
                        • Modular architecture ensuring
                        <b>resilience and scalability</b>.<br />
                        • Centralized control, full auditability, and real-time
                        observability.
                    </div>
                </div>

                <!-- Service status pills -->
                <div class="row q-gutter-sm justify-center q-mt-md">
                    <q-badge
                        v-for="s in services"
                        :key="s.id"
                        :color="s.up ? 'green-6' : 'red-6'"
                        outline
                    >
                        <q-icon :name="s.icon" class="q-mr-xs" /> {{ s.name }} •
                        {{ s.up ? "Online" : "Down" }}
                    </q-badge>
                </div>
            </q-page>
        </template>
    </v-guest-layout>
</template>

<script>
export default {
    name: "CA_LandingDashboard",
    data() {
        return {
            orbitApps: [
                {
                    id: "cloud",
                    name: "Cloud",
                    desc: "Compute, Storage, IAM",
                    icon: "mdi-cloud-outline",
                    href: "/cloud",
                },
                {
                    id: "vpn",
                    name: "VPN",
                    desc: "Secure network access",
                    icon: "mdi-shield-lock",
                    href: "/vpn",
                },
                {
                    id: "ecommerce",
                    name: "eCommerce",
                    desc: "Billing & orders",
                    icon: "mdi-cart-outline",
                    href: "/ecommerce",
                },
                {
                    id: "x",
                    name: "App X",
                    desc: "External OIDC app",
                    icon: "mdi-application",
                    href: "/x",
                },
            ],
            coreModules: [
                {
                    id: "oauth",
                    name: "OAuth2/OIDC",
                    desc: "Tokens, clients, scopes, claims",
                    icon: "mdi-key-chain-variant",
                    enabled: true,
                },
                {
                    id: "access",
                    name: "Access Control",
                    desc: "Groups • Services • Roles by app",
                    icon: "mdi-shield-account",
                    enabled: true,
                },
                {
                    id: "audit",
                    name: "Audit Logs",
                    desc: "Sign-ins, grants, policy hits",
                    icon: "mdi-clipboard-text-outline",
                    enabled: true,
                },
            ],
            thirdModules: [
                {
                    id: "partner",
                    name: "Partner",
                    desc: "Partner onboarding & SSO",
                    icon: "mdi-account-tie-outline",
                    isolated: true,
                },
                {
                    id: "notifier",
                    name: "Notifier",
                    desc: "Hooks & webhooks",
                    icon: "mdi-bell-ring-outline",
                    isolated: true,
                },
            ],
            services: [
                {
                    id: "passport",
                    name: "Passport",
                    icon: "mdi-passport",
                    up: true,
                },
                {
                    id: "oidc",
                    name: "OpenID Provider",
                    icon: "mdi-shield-lock-outline",
                    up: true,
                },
                { id: "db", name: "DB", icon: "mdi-database", up: true },
                {
                    id: "queue",
                    name: "Queue",
                    icon: "mdi-clipboard-flow-outline",
                    up: true,
                },
            ],
        };
    },
    methods: {
        go(to) {
            window.location.href = to;
        },
        nodeStyle(index, total) {
            const angle = (360 / total) * index;
            const radius = 220;
            return {
                transform: `rotate(${angle}deg) translate(${radius}px) rotate(-${angle}deg)`,
            };
        },
    },
};
</script>

<style scoped lang="css">
.ca-landing {
    min-height: 100vh;
    background: radial-gradient(
            1200px 600px at 20% -10%,
            rgba(66, 135, 245, 0.25),
            transparent 60%
        ),
        linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
    color: #fff;
    font-family: "Inter", system-ui, -apple-system, Segoe UI, Roboto,
        "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji",
        "Segoe UI Emoji", "Segoe UI Symbol";
}
.glass {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(8px);
    border-radius: 16px;
}
.panel {
    min-height: 260px;
}

.orbital-container {
    position: relative;
    width: 100%;
    display: flex;
    justify-content: center;
    margin: 6em 0;
}

.orbital-system {
    position: relative;
    width: 90vmin;
    height: 90vmin;
    max-width: 600px;
    max-height: 600px;
    min-width: 280px;
    min-height: 280px;
    margin: 0 auto;
}

.center-node {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 240px;
    z-index: 2;
}

.central-server {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 25vmin;
    height: 25vmin;
    max-width: 180px;
    max-height: 180px;
    min-width: 100px;
    min-height: 100px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    box-shadow: 0 0 0 10px rgba(74, 111, 165, 0.2),
        0 0 30px rgba(74, 111, 165, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.1);
    z-index: 10;
    transition: all 0.5s ease;
    animation: pulse 4s infinite ease-in-out;
}

.central-server:hover {
    transform: translate(-50%, -50%) scale(1.05);
    box-shadow: 0 0 0 10px rgba(74, 111, 165, 0.3),
        0 0 40px rgba(74, 111, 165, 0.6);
}

.server-icon {
    font-size: 8vmin;
    max-font-size: 48px;
    min-font-size: 24px;
    color: var(--accent);
    margin-bottom: 1vmin;
}

.server-title {
    font-weight: 600;
    font-size: 2.5vmin;
    max-font-size: 18px;
    min-font-size: 12px;
    margin: 0;
    text-align: center;
}

.server-subtitle {
    font-size: 2vmin;
    max-font-size: 12px;
    min-font-size: 9px;
    opacity: 0.8;
    margin: 5px 0 0;
    text-align: center;
}

.orbit {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 50%;
}

.orbit-1 {
    width: 60vmin;
    height: 60vmin;
    max-width: 400px;
    max-height: 400px;
    min-width: 200px;
    min-height: 200px;
}

.orbit-2 {
    width: 75vmin;
    height: 75vmin;
    max-width: 500px;
    max-height: 500px;
    min-width: 250px;
    min-height: 250px;
}

.orbit-3 {
    width: 90vmin;
    height: 90vmin;
    max-width: 600px;
    max-height: 600px;
    min-width: 280px;
    min-height: 280px;
}

.app-node {
    position: absolute;
    width: 20vmin;
    height: 15vmin;
    max-width: 150px;
    max-height: 100px;
    min-width: 70px;
    min-height: 60px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.1);
    cursor: pointer;
    transition: all 0.3s ease;
    transform-origin: center;
    z-index: 5;
    transition: all 0.5s ease;
    animation: pulseIcon 4s infinite ease-in-out;
    margin-bottom: 1.5em;
}

.app-node:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    background: rgba(255, 255, 255, 0.1);
}

.app-icon {
    font-size: 4vmin;
    max-font-size: 28px;
    min-font-size: 16px;
    margin-bottom: 1vmin;
}

.app-title {
    font-weight: 500;
    font-size: 2vmin;
    max-font-size: 14px;
    min-font-size: 9px;
    margin: 0;
    text-align: center;
    line-height: 1.2;
}

.node-1 {
    top: 0;
    left: 50%;
    transform: translate(-50%, -50%);
    background: linear-gradient(
        135deg,
        rgba(106, 27, 154, 0.1) 0%,
        rgba(49, 27, 146, 0.2) 100%
    );
    border-color: rgba(106, 27, 154, 0.3);
}

.node-2 {
    top: 50%;
    right: 0;
    transform: translate(50%, -50%);
    background: linear-gradient(
        135deg,
        rgba(0, 150, 136, 0.1) 0%,
        rgba(0, 77, 64, 0.2) 100%
    );
    border-color: rgba(0, 150, 136, 0.3);
}

.node-3 {
    bottom: 0;
    left: 50%;
    transform: translate(-50%, 50%);
    background: linear-gradient(
        135deg,
        rgba(244, 67, 54, 0.1) 0%,
        rgba(183, 28, 28, 0.2) 100%
    );
    border-color: rgba(244, 67, 54, 0.3);
}

.node-4 {
    top: 50%;
    left: 0;
    transform: translate(-50%, -50%);
    background: linear-gradient(
        135deg,
        rgba(255, 152, 0, 0.1) 0%,
        rgba(245, 124, 0, 0.2) 100%
    );
    border-color: rgba(255, 152, 0, 0.3);
}

.node-5 {
    top: 15%;
    right: 15%;
    background: linear-gradient(
        135deg,
        rgba(156, 39, 176, 0.1) 0%,
        rgba(103, 58, 183, 0.2) 100%
    );
    border-color: rgba(156, 39, 176, 0.3);
}

.node-6 {
    top: 15%;
    left: 15%;
    background: linear-gradient(
        135deg,
        rgba(3, 169, 244, 0.1) 0%,
        rgba(3, 155, 229, 0.2) 100%
    );
    border-color: rgba(3, 169, 244, 0.3);
}

.node-7 {
    bottom: 15%;
    right: 15%;
    background: linear-gradient(
        135deg,
        rgba(205, 220, 57, 0.1) 0%,
        rgba(175, 180, 43, 0.2) 100%
    );
    border-color: rgba(205, 220, 57, 0.3);
}

.node-8 {
    bottom: 15%;
    left: 15%;
    background: linear-gradient(
        135deg,
        rgba(255, 87, 34, 0.1) 0%,
        rgba(230, 74, 25, 0.2) 100%
    );
    border-color: rgba(255, 87, 34, 0.3);
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 10px rgba(74, 111, 165, 0.2),
            0 0 30px rgba(74, 111, 165, 0.4);
    }

    50% {
        box-shadow: 0 0 0 15px rgba(74, 111, 165, 0.3),
            0 0 40px rgba(74, 111, 165, 0.6);
    }

    100% {
        box-shadow: 0 0 0 10px rgba(74, 111, 165, 0.2),
            0 0 30px rgba(74, 111, 165, 0.4);
    }
}

@keyframes pulseIcon {
    0% {
        box-shadow: 0 0 0 4px rgba(74, 111, 165, 0.15),
            0 0 15px rgba(74, 111, 165, 0.25);
    }

    50% {
        box-shadow: 0 0 0 6px rgba(74, 111, 165, 0.2),
            0 0 20px rgba(74, 111, 165, 0.35);
    }

    100% {
        box-shadow: 0 0 0 4px rgba(74, 111, 165, 0.15),
            0 0 15px rgba(74, 111, 165, 0.25);
    }
}

@keyframes orbit {
    from {
        transform: translate(-50%, -50%) rotate(0deg);
    }

    to {
        transform: translate(-50%, -50%) rotate(360deg);
    }
}

/* Responsive adjustments */
@media (max-width: 1024px) {
    .orbital-system {
        width: 80vmin;
        height: 80vmin;
    }

    .app-node {
        width: 18vmin;
        height: 14vmin;
    }
}

@media (max-width: 768px) {
    .ca-landing {
        padding: 16px;
    }

    .orbital-system {
        width: 70vmin;
        height: 70vmin;
    }

    .app-node {
        width: 16vmin;
        height: 12vmin;
        border-radius: 8px;
    }

    .orbit-1,
    .orbit-2,
    .orbit-3 {
        display: none;
    }
}

@media (max-width: 480px) {
    .orbital-system {
        width: 60vmin;
        height: 60vmin;
    }

    .app-node {
        width: 14vmin;
        height: 10vmin;
    }

    .server-title {
        font-size: 2vmin;
    }

    .server-subtitle {
        font-size: 1.8vmin;
    }

    .app-title {
        font-size: 1.8vmin;
    }
}

.q-badge {
    border-radius: 999px;
    margin: 4px;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>
