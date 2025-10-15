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
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
        <!-- Header   -->
        <header
            class="bg-white/95 backdrop-blur-md shadow-lg border-b border-gray-200/60 sticky top-0 z-50"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo y Branding -->
                    <div class="flex items-center space-x-4">
                        <button
                            @click="homePage"
                            class="group p-3 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-blue-500/30"
                        >
                            <svg
                                class="w-6 h-6 transform group-hover:scale-110 transition-transform"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                />
                            </svg>
                        </button>
                        <div class="flex flex-col">
                            <span
                                class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent"
                            >
                                {{ $page.props.app_name }}
                            </span>
                            <span class="text-xs text-gray-500 font-medium">{{
                                __("Premium Platform")
                            }}</span>
                        </div>
                    </div>

                    <!-- Navigation Actions -->
                    <div class="flex items-center space-x-3">
                        <v-theme />

                        <!-- Plan Button -->
                        <button
                            v-if="plan?.name"
                            @click="open(plan?.route)"
                            class="group px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 hover:from-emerald-600 hover:to-green-700 focus:outline-none focus:ring-4 focus:ring-emerald-500/30"
                        >
                            <span class="flex items-center space-x-2">
                                <svg
                                    class="w-4 h-4 group-hover:animate-pulse"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"
                                    />
                                </svg>
                                <span>{{ __(plan?.name) }}</span>
                            </span>
                        </button>

                        <!-- Auth Buttons -->
                        <div
                            v-if="!$page.props.user?.id"
                            class="flex items-center space-x-3"
                        >
                            <button
                                v-if="$page.props.allow_register"
                                @click="
                                    open($page.props.auth_routes['register'])
                                "
                                class="px-6 py-3 border-2 border-blue-500 text-blue-600 rounded-xl font-semibold hover:bg-blue-500 hover:text-white transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-blue-500/30"
                            >
                                {{ __("Register") }}
                            </button>
                            <button
                                @click="open($page.props.auth_routes['login'])"
                                class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-4 focus:ring-blue-500/30"
                            >
                                {{ __("Login") }}
                            </button>
                        </div>

                        <!-- User Profile -->
                        <div class="flex items-center space-x-4">
                            <v-notification />
                            <v-profile />
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div
                class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/60 p-8"
            >
                <slot />
            </div>
        </main>

        <!-- Floating Elements for Visual Interest -->
        <div
            class="fixed top-1/4 left-5 w-4 h-4 bg-blue-400/30 rounded-full animate-pulse"
        ></div>
        <div
            class="fixed top-1/3 right-10 w-6 h-6 bg-purple-400/20 rounded-full animate-bounce"
        ></div>
        <div
            class="fixed bottom-1/4 left-10 w-3 h-3 bg-emerald-400/40 rounded-full animate-pulse delay-1000"
        ></div>
    </div>
</template>

<script>
import VNotification from "@/components/VNotification.vue";
import VProfile from "@/components/VProfile.vue";

export default {
    components: {
        VNotification,
        VProfile,
    },
    data() {
        return {
            guest: false,
            plans: {},
        };
    },

    created() {
        this.plan = this.$page.props.routes?.plans;
    },

    methods: {
        open(url) {
            const currentUrl = window.location.href;

            const path = url.startsWith("/")
                ? url
                : new URL(url, window.location.origin).pathname;

            if (
                path.startsWith("/auth/login") ||
                path.startsWith("/auth/register")
            ) {
                const redirectUrl = new URL(url, window.location.origin);
                redirectUrl.searchParams.set("redirect_to", currentUrl);
                window.location.href = redirectUrl.toString();
            } else {
                window.location.href = url;
            }
        },

        isActive(item) {
            return item.route == window.location.href;
        },

        homePage() {
            window.location.href = "/";
        },
    },
};
</script>

<style scoped>
/* Smooth scrolling for the entire page */
html {
    scroll-behavior: smooth;
}

/* Custom gradient animation for background */
.min-h-screen {
    background: linear-gradient(-45deg, #f8fafc, #f1f5f9, #e0f2fe, #f0f9ff);
    background-size: 400% 400%;
    animation: gradientShift 15s ease infinite;
}

@keyframes gradientShift {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

/* Glass morphism effect for header */
header {
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
}

/* Smooth shadow transitions */
.shadow-lg {
    transition: box-shadow 0.3s ease;
}

/* Custom hover effects */
button {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Enhanced focus states */
button:focus {
    outline: 2px solid transparent;
    outline-offset: 2px;
}
</style>
