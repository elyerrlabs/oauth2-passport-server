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
    <div class="min-h-screen bg-white dark:bg-gray-900 flex">
        <!-- Sidebar Overlay (Mobile) -->
        <div
            v-if="isSidebarOpen"
            class="fixed inset-0 bg-black/80 z-40 lg:hidden"
            @click="toggleMenu"
        ></div>

        <!-- Enhanced Sidebar - Fixed on desktop -->
        <aside
            class="fixed lg:sticky overflow-auto min-h-screen max-h-screen inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-xl transform transition-all duration-300 ease-in-out border-r border-gray-200 dark:border-gray-700 flex flex-col"
            :class="[
                // Mobile behavior
                isSidebarOpen ? 'translate-x-0' : '-translate-x-full',
                // Desktop behavior - show when aside is true, hide when false
                aside
                    ? 'lg:translate-x-0'
                    : 'lg:-translate-x-full lg:w-0 lg:overflow-hidden',
            ]"
        >
            <div
                class="p-4 border-b border-gray-200 dark:border-gray-700 shrink-0"
                v-if="$page.props.user && aside"
            >
                <div class="flex items-center space-x-3">
                    <div
                        class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-lg"
                    >
                        {{ userInitials }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p
                            class="text-sm font-semibold text-gray-900 dark:text-white truncate"
                        >
                            {{ user.name }}
                        </p>
                        <p
                            class="text-sm text-gray-500 dark:text-gray-400 truncate"
                        >
                            {{ user.email }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Scrollable Navigation -->
            <div class="flex-1 overflow-y-auto" v-if="aside">
                <nav class="p-4 space-y-2">
                    <!-- Account Section -->
                    <div>
                        <h3
                            class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"
                        >
                            {{ __("Account") }}
                        </h3>
                        <button
                            @click="open($page.props.user_dashboard)"
                            class="w-full flex items-center space-x-3 px-3 py-2 cursor-pointer text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200"
                        >
                            <div
                                class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center"
                            >
                                <i
                                    :class="[
                                        'mdi',
                                        $page.props.user_dashboard.icon,
                                        'text-white text-sm',
                                    ]"
                                ></i>
                            </div>
                            <span>{{ __("Home") }}</span>
                        </button>
                    </div>

                    <slot name="aside" />
                </nav>
            </div>

            <!-- Footer -->
            <div
                class="border-t border-gray-200 dark:border-gray-700 p-4 shrink-0"
                v-if="aside"
            >
                <p
                    class="text-xs text-center text-gray-500 dark:text-gray-400 font-medium"
                >
                    &copy; {{ new Date().getFullYear() }}
                    {{ $page.props.org_name }}
                </p>
            </div>
        </aside>

        <!-- Main Content -->
        <div
            class="flex flex-col min-h-screen min-w-0 flex-1 transition-all duration-300"
        >
            <!-- Enhanced Header -->
            <header
                class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 z-30 sticky top-0"
            >
                <div
                    class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8"
                >
                    <div class="flex items-center space-x-4">
                        <!-- Mobile menu button -->
                        <button
                            @click="toggleMenu"
                            class="lg:hidden p-2 rounded-md text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
                        >
                            <i class="mdi mdi-menu text-lg"></i>
                        </button>

                        <!-- Toggle Sidebar Button (Desktop) -->
                        <button
                            @click="toggle"
                            class="w-8 h-8 bg-blue-600 rounded-lg hidden lg:flex items-center justify-center hover:bg-blue-700 transition-colors duration-200"
                            :title="
                                aside ? __('Hide sidebar') : __('Show sidebar')
                            "
                        >
                            <i
                                :class="[
                                    'mdi text-white text-sm transition-transform duration-300',
                                    aside
                                        ? 'mdi-chevron-left'
                                        : 'mdi-chevron-right',
                                ]"
                            ></i>
                        </button>

                        <!-- App Title -->
                        <h1
                            class="text-xl font-semibold text-gray-900 dark:text-white"
                        >
                            {{ app_name }}
                        </h1>
                    </div>

                    <div class="flex items-center space-x-4">
                        <v-theme />
                        <v-notification />
                        <v-profile />
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto">
                <div class="p-2 sm:p-4 lg:p-8">
                    <slot name="main" />
                </div>
            </main>
        </div>
    </div>
</template>
<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import VNotification from "@/components/VNotification.vue";
import VProfile from "@/components/VProfile.vue";
import VTheme from "@/components/VTheme.vue";

const page = usePage();

// Reactive state
const isSidebarOpen = ref(false);
const user = ref(page.props.user ?? {});
const app_name = ref(page.props.org_name ?? "");
const aside = ref(true); // true = sidebar visible, false = hidden

// Computed: get user initials
const userInitials = computed(() => {
    if (!user.value || !user.value.name) return "U";
    return user.value.name
        .split(" ")
        .map((n) => n[0])
        .join("")
        .toUpperCase();
});

// Load sidebar visibility from localStorage
const savedState = localStorage.getItem("sidebarVisible");
if (savedState !== null) {
    aside.value = JSON.parse(savedState);
}

// Methods
const toggleMenu = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const open = (item) => {
    router.visit(item.route);
};

const toggle = () => {
    aside.value = !aside.value;
    localStorage.setItem("sidebarVisible", aside.value);

    // Close mobile menu if it's open
    if (isSidebarOpen.value) {
        isSidebarOpen.value = false;
    }
};

const handleResize = () => {
    // Always close the mobile sidebar on resize
    if (window.innerWidth >= 1024) {
        isSidebarOpen.value = false;
    } else {
        isSidebarOpen.value = false;
    }
};

// Setup window event listeners
const setupEventListeners = () => {
    window.addEventListener("resize", handleResize);
    handleResize(); // Initial check
};

// Lifecycle hooks
onMounted(() => {
    setupEventListeners();
});

onBeforeUnmount(() => {
    window.removeEventListener("resize", handleResize);
});
</script>
