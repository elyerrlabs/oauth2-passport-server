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
    <div class="relative inline-block text-left">
        <!-- Theme Toggle Button -->
        <button
            @click="toggleDropdown"
            class="flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 shadow-sm hover:shadow-md hover:border-gray-400 dark:hover:border-gray-500 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 group"
        >
            <!-- Animated Icon -->
            <div class="relative w-5 h-5">
                <!-- Sun (Light) -->
                <i
                    class="mdi mdi-white-balance-sunny text-yellow-500 absolute inset-0 transition-all duration-300 transform"
                    :class="[
                        selectedTheme === 'light'
                            ? 'opacity-100 rotate-0 scale-100'
                            : 'opacity-0 rotate-90 scale-50',
                        selectedTheme === 'auto' ? 'opacity-50' : '',
                    ]"
                ></i>
                <!-- Moon (Dark) -->
                <i
                    class="mdi mdi-moon-waning-crescent text-blue-400 absolute inset-0 transition-all duration-300 transform"
                    :class="[
                        selectedTheme === 'dark'
                            ? 'opacity-100 rotate-0 scale-100'
                            : 'opacity-0 -rotate-90 scale-50',
                        selectedTheme === 'auto' ? 'opacity-50' : '',
                    ]"
                ></i>
                <!-- Auto -->
                <i
                    class="mdi mdi-theme-light-dark text-purple-500 absolute inset-0 transition-all duration-300 transform"
                    :class="[
                        selectedTheme === 'auto'
                            ? 'opacity-100 rotate-0 scale-100'
                            : 'opacity-0 rotate-180 scale-50',
                    ]"
                ></i>
            </div>

            <span class="sr-only">{{ __("Toggle theme") }}</span>
        </button>

        <!-- Dropdown Menu -->
        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95 translate-y-2"
            enter-to-class="opacity-100 scale-100 translate-y-0"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 scale-100 translate-y-0"
            leave-to-class="opacity-0 scale-95 translate-y-2"
        >
            <div
                v-show="isOpen"
                @click.outside="closeDropdown"
                class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-xl ring-1 ring-black/5 dark:ring-white/10 z-50 overflow-hidden"
            >
                <!-- Header -->
                <div
                    class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900/50"
                >
                    <div class="flex items-center space-x-2">
                        <i class="mdi mdi-palette text-purple-500"></i>
                        <span
                            class="text-sm font-semibold text-gray-900 dark:text-white"
                        >
                            {{ __("Theme") }}
                        </span>
                    </div>
                </div>

                <!-- Theme Options -->
                <div class="py-1">
                    <button
                        v-for="theme in themes"
                        :key="theme.value"
                        @click="selectTheme(theme.value)"
                        class="w-full flex items-center space-x-3 px-4 py-3 text-sm transition-all duration-200 group"
                        :class="[
                            selectedTheme === theme.value
                                ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-700/50',
                        ]"
                    >
                        <!-- Theme Icon -->
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors duration-200"
                                :class="[
                                    selectedTheme === theme.value
                                        ? theme.activeBg
                                        : theme.inactiveBg,
                                ]"
                            >
                                <i
                                    class="text-lg"
                                    :class="[
                                        theme.icon,
                                        selectedTheme === theme.value
                                            ? theme.activeColor
                                            : theme.inactiveColor,
                                    ]"
                                ></i>
                            </div>
                        </div>

                        <!-- Theme Info -->
                        <div class="flex-1 text-left">
                            <div class="font-medium">
                                {{ __(theme.label) }}
                            </div>
                            <div class="text-xs opacity-70 mt-0.5">
                                {{ __(theme.description) }}
                            </div>
                        </div>

                        <!-- Selected Indicator -->
                        <div
                            v-if="selectedTheme === theme.value"
                            class="flex-shrink-0"
                        >
                            <i
                                class="mdi mdi-check text-blue-500 dark:text-blue-400 text-lg"
                            ></i>
                        </div>
                    </button>
                </div>

                <!-- Footer -->
                <div
                    class="px-4 py-2 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900/50"
                >
                    <div
                        class="text-xs text-gray-500 dark:text-gray-400 text-center"
                    >
                        {{ __("Changes apply instantly") }}
                    </div>
                </div>
            </div>
        </transition>

        <!-- Current Theme Badge (Desktop only)
        <div class="hidden lg:flex items-center space-x-2 ml-3">
            <span class="text-sm text-gray-500 dark:text-gray-400">
                {{ __("Theme:") }}
            </span>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ currentThemeLabel }}
            </span>
        </div> -->
    </div>
</template>

<script>
export default {
    name: "ThemeToggle",
    data() {
        return {
            isOpen: false,
            selectedTheme: "light",
            themes: [
                {
                    label: "Light",
                    value: "light",
                    icon: "mdi mdi-white-balance-sunny",
                    description: "Bright and clean",
                    activeBg: "bg-yellow-100",
                    inactiveBg: "bg-gray-100 dark:bg-gray-700",
                    activeColor: "text-yellow-600",
                    inactiveColor: "text-gray-500 dark:text-gray-400",
                },
                {
                    label: "Dark",
                    value: "dark",
                    icon: "mdi mdi-moon-waning-crescent",
                    description: "Easy on the eyes",
                    activeBg: "bg-blue-900/20",
                    inactiveBg: "bg-gray-100 dark:bg-gray-700",
                    activeColor: "text-blue-400",
                    inactiveColor: "text-gray-500 dark:text-gray-400",
                },
                {
                    label: "Auto",
                    value: "auto",
                    icon: "mdi mdi-theme-light-dark",
                    description: "Follow system",
                    activeBg: "bg-purple-100 dark:bg-purple-900/20",
                    inactiveBg: "bg-gray-100 dark:bg-gray-700",
                    activeColor: "text-purple-600 dark:text-purple-400",
                    inactiveColor: "text-gray-500 dark:text-gray-400",
                },
            ],
        };
    },
    computed: {
        currentThemeLabel() {
            const theme = this.themes.find(
                (t) => t.value === this.selectedTheme
            );
            return theme ? __(theme.label) : "";
        },
    },
    mounted() {
        // Load saved theme or detect system preference
        const savedTheme = localStorage.getItem("theme");
        const systemPrefersDark = window.matchMedia(
            "(prefers-color-scheme: dark)"
        ).matches;

        if (savedTheme) {
            this.selectedTheme = savedTheme;
        } else {
            this.selectedTheme = "auto";
        }

        this.applyTheme(this.selectedTheme);

        // Listen for system theme changes
        window
            .matchMedia("(prefers-color-scheme: dark)")
            .addEventListener("change", (e) => {
                if (this.selectedTheme === "auto") {
                    this.applyTheme("auto");
                }
            });
    },
    methods: {
        toggleDropdown() {
            this.isOpen = !this.isOpen;
        },

        closeDropdown() {
            this.isOpen = false;
        },

        selectTheme(theme) {
            this.selectedTheme = theme;
            this.applyTheme(theme);
            this.closeDropdown();
        },

        applyTheme(theme) {
            let effectiveTheme = theme;

            if (theme === "auto") {
                effectiveTheme = window.matchMedia(
                    "(prefers-color-scheme: dark)"
                ).matches
                    ? "dark"
                    : "light";
            }

            // Apply to document
            document.documentElement.classList.remove("light", "dark");
            document.documentElement.classList.add(effectiveTheme);

            // Update meta theme-color
            this.updateMetaThemeColor(effectiveTheme);

            // Save preference
            localStorage.setItem("theme", theme);

            // Emit event for other components
            this.$emit("theme-changed", effectiveTheme);
        },

        updateMetaThemeColor(theme) {
            let themeColor = "#ffffff";

            if (theme === "dark") {
                themeColor = "#1f2937"; // gray-800
            } else if (theme === "light") {
                themeColor = "#ffffff";
            }

            let metaThemeColor = document.querySelector(
                'meta[name="theme-color"]'
            );
            if (!metaThemeColor) {
                metaThemeColor = document.createElement("meta");
                metaThemeColor.name = "theme-color";
                document.head.appendChild(metaThemeColor);
            }
            metaThemeColor.content = themeColor;
        },

        handleEscapeKey(event) {
            if (event.key === "Escape" && this.isOpen) {
                this.closeDropdown();
            }
        },
    },

    watch: {
        isOpen(val) {
            if (val) {
                document.addEventListener("keydown", this.handleEscapeKey);
            } else {
                document.removeEventListener("keydown", this.handleEscapeKey);
            }
        },
    },

    beforeUnmount() {
        document.removeEventListener("keydown", this.handleEscapeKey);
    },
};
</script>

<style scoped>
/* Smooth transitions for all interactive elements */
button,
div,
span {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Custom focus styles */
button:focus {
    outline: 2px solid transparent;
    outline-offset: 2px;
}
</style>
