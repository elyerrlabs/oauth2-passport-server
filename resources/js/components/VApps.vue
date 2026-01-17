<!--
OAuth2 Passport Server — a centralized, modular authorization server
implementing OAuth 2.0 and OpenID Connect specifications.

Copyright (c) 2026 Elvis Yerel Roman Concha

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program. If not, see <https://www.gnu.org/licenses/>.

Author: Elvis Yerel Roman Concha
Contact: yerel9212@yahoo.es

SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
    <div class="p-4 sm:p-5">
        <!-- Header Section - Simplified -->
        <div class="flex items-start sm:items-center gap-3 mb-6">
            <div class="flex-shrink-0">
                <div
                    class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900 rounded-lg flex items-center justify-center"
                >
                    <i
                        class="mdi mdi-cog-outline text-emerald-600 dark:text-emerald-400 text-base"
                    ></i>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <h2
                    class="text-lg font-semibold text-slate-900 dark:text-white mb-1"
                >
                    {{ __(title) }}
                </h2>
                <p class="text-sm text-slate-600 dark:text-slate-400">
                    {{ __(description) }}
                </p>
            </div>
        </div>

        <!-- Apps Grid - Optimized for Mobile -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div
                v-for="(app, index) in apps"
                :key="index"
                class="group relative bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg hover:shadow transition-colors duration-150"
            >
                <!-- Card Content -->
                <div class="p-4">
                    <!-- Header with Icon and Action -->
                    <div class="flex items-start justify-between mb-3">
                        <div
                            class="w-10 h-10 bg-emerald-500 dark:bg-emerald-600 rounded-lg flex items-center justify-center shadow-sm"
                        >
                            <i
                                :class="['mdi text-white text-lg', app.icon]"
                            ></i>
                        </div>
                        <a
                            :href="app.route"
                            class="inline-flex items-center justify-center w-6 h-6 text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors"
                            :aria-label="__('Go to') + ' ' + app.name"
                        >
                            <i class="mdi mdi-chevron-right text-base"></i>
                        </a>
                    </div>

                    <!-- App Info -->
                    <div class="mb-4">
                        <h3
                            class="font-medium text-slate-900 dark:text-white text-sm mb-2 line-clamp-1"
                        >
                            {{ __(app.name) }}
                        </h3>
                        <p
                            v-if="app.description"
                            class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2"
                        >
                            {{ __(app.description) }}
                        </p>
                    </div>

                    <!-- Action Button -->
                    <a
                        :href="app.route"
                        class="block w-full text-center bg-slate-50 dark:bg-slate-700 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 py-2 px-3 rounded-md font-medium text-sm transition-colors duration-150 border border-slate-200 dark:border-slate-600 hover:border-emerald-300 dark:hover:border-emerald-500"
                    >
                        <span class="inline-flex items-center gap-1">
                            <span>{{ __("Open") }}</span>
                            <i class="mdi mdi-arrow-right text-xs"></i>
                        </span>
                    </a>
                </div>

                <!-- Hover Indicator -->
                <div
                    class="absolute inset-0 border-2 border-transparent group-hover:border-emerald-300 dark:group-hover:border-emerald-500 rounded-lg pointer-events-none transition-colors duration-150"
                ></div>
            </div>
        </div>

        <!-- Empty State -->
        <div
            v-if="!apps || apps.length === 0"
            class="text-center py-8 px-4 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700"
        >
            <div
                class="w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center mx-auto mb-3"
            >
                <i
                    class="mdi mdi-apps text-slate-400 dark:text-slate-500 text-xl"
                ></i>
            </div>
            <h3
                class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1"
            >
                {{ __("No applications available") }}
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-500">
                {{ __("Check back later for new options") }}
            </p>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    apps: {
        type: Array,
        default: () => [],
    },
    title: {
        type: String,
        default: () => "Applications",
    },
    description: {
        type: String,
        default: () => "Manage your apps and settings",
    },
});
</script>
