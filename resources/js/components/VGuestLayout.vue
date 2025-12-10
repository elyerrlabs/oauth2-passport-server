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
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Header -->
        <header
            class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 sticky top-0 z-50"
        >
            <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo and Branding -->
                    <div class="flex items-center gap-2 sm:gap-3">
                        <button
                            @click="homePage"
                            class="p-2 sm:p-3 rounded-lg bg-blue-600 dark:bg-blue-700 text-white hover:bg-blue-700 dark:hover:bg-blue-600 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600"
                            aria-label="Home"
                        >
                            <svg
                                class="w-5 h-5 sm:w-6 sm:h-6"
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
                                class="text-base sm:text-lg lg:text-xl font-bold text-gray-900 dark:text-white truncate"
                            >
                                {{ $page.props.app_name }}
                            </span>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400"
                            >
                                {{ __("Premium Platform") }}
                            </span>
                        </div>
                    </div>

                    <!-- Navigation Actions -->
                    <div class="flex items-center gap-2 sm:gap-3">
                        <!-- Plan Button -->
                        <button
                            v-if="plan?.name"
                            @click="open(plan?.route)"
                            class="px-3 sm:px-4 py-2 bg-green-600 dark:bg-green-700 text-white rounded-lg font-medium hover:bg-green-700 dark:hover:bg-green-600 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 dark:focus:ring-green-600 text-sm sm:text-base"
                        >
                            <span class="flex items-center gap-1 sm:gap-2">
                                <svg
                                    class="w-3 h-3 sm:w-4 sm:h-4"
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
                                <span
                                    class="truncate max-w-[80px] sm:max-w-none"
                                    >{{ __(plan?.name) }}</span
                                >
                            </span>
                        </button>

                        <!-- Auth Buttons -->
                        <div
                            v-if="!$page.props.user?.id"
                            class="hidden lg:flex items-center gap-2"
                        >
                            <button
                                v-if="$page.props.allow_register"
                                @click="
                                    open($page.props.auth_routes['register'])
                                "
                                class="px-3 sm:px-4 py-2 border border-blue-500 dark:border-blue-600 text-blue-600 dark:text-blue-400 rounded-lg font-medium hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 text-sm sm:text-base"
                            >
                                {{ __("Register") }}
                            </button>
                            <button
                                @click="open($page.props.auth_routes.login)"
                                class="px-3 sm:px-4 py-2 bg-blue-600 dark:bg-blue-700 text-white rounded-lg font-medium hover:bg-blue-700 dark:hover:bg-blue-600 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 text-sm sm:text-base"
                            >
                                {{ __("Login") }}
                            </button>
                        </div>

                        <!-- User Profile -->
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div>
                                <v-theme />
                            </div>
                            <div class="hidden sm:block">
                                <v-notification />
                            </div>
                            <v-profile />
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="max-w-7xl mx-auto py-4 px-3 sm:px-4 lg:px-6">
            <div
                class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow border border-gray-200 dark:border-gray-700"
            >
                <slot />
            </div>
        </main>

        <!-- Mobile Auth Buttons -->
        <div
            v-if="!$page.props.user?.id"
            class="lg:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 p-3 z-40"
        >
            <div class="flex gap-2 max-w-7xl mx-auto">
                <button
                    v-if="$page.props.allow_register"
                    @click="open($page.props.auth_routes['register'])"
                    class="flex-1 px-4 py-3 border border-blue-500 dark:border-blue-600 text-blue-600 dark:text-blue-400 rounded-lg font-medium hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600"
                >
                    {{ __("Register") }}
                </button>
                <button
                    @click="open($page.props.auth_routes.login)"
                    class="flex-1 px-4 py-3 bg-blue-600 dark:bg-blue-700 text-white rounded-lg font-medium hover:bg-blue-700 dark:hover:bg-blue-600 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600"
                >
                    {{ __("Login") }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import VNotification from "@/components/VNotification.vue";
import VProfile from "@/components/VProfile.vue";
import VTheme from "@/components/VTheme.vue";

export default {
    components: {
        VNotification,
        VProfile,
        VTheme,
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
            if (!item?.route) return false;
            const currentPath = window.location.pathname;
            const itemPath = new URL(item.route, window.location.origin)
                .pathname;
            return currentPath === itemPath;
        },

        homePage() {
            window.location.href = "/";
        },
    },
};
</script>

<style>
/* Performance optimizations */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* Touch improvements */
* {
    -webkit-tap-highlight-color: transparent;
}

/* Better scrolling */
html {
    scroll-behavior: smooth;
}

/* Responsive text sizing */
@media (max-width: 640px) {
    .text-truncate-mobile {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
}
</style>
