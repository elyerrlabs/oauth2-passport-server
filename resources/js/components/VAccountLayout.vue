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
    <v-layout>
        <template #aside>
            <!-- Dashboards Section -->
            <div class="mb-6" v-if="$page.props.admin_dashboard?.length">
                <h3
                    class="text-xs font-semibold text-gray-500 flex items-center dark:text-gray-400 uppercase tracking-wider mb-3"
                >
                    <span
                        class="mdi mdi-shield-crown-outline text-2xl me-2"
                    ></span>
                    {{ __("Administrator") }}
                </h3>
                <div class="space-y-1">
                    <button
                        v-for="(item, index) in $page.props.admin_dashboard"
                        :key="index"
                        @click="open(item)"
                        class="group w-full flex items-center cursor-pointer gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50"
                        :class="{
                            'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm':
                                isActive(item),
                        }"
                    >
                        <div
                            class="w-8 h-8 flex items-center justify-center rounded-xl bg-gray-200 dark:bg-gray-700 transition-all duration-200 group-hover:scale-110 group-hover:bg-gray-300 dark:group-hover:bg-gray-600"
                        >
                            <i
                                :class="[
                                    'mdi',
                                    item.icon,
                                    'text-gray-600 dark:text-gray-300 text-base',
                                ]"
                            ></i>
                        </div>
                        <span class="font-medium">{{ __(item.name) }}</span>
                    </button>
                </div>
            </div>

            <!-- Developers Section -->
            <div
                v-if="$page.props.developers.show"
                class="pt-2 border-t border-gray-200 dark:border-gray-700"
            >
                <h3
                    class="text-xs font-semibold flex items-center text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3 mt-2"
                >
                    <span
                        class="mdi mdi-application-brackets-outline text-2xl me-2"
                    ></span>
                    {{ __($page.props.developers.name) }}
                </h3>
                <div class="space-y-1">
                    <button
                        v-for="(item, index) in $page.props.developers.menu"
                        :key="index"
                        @click="open(item)"
                        class="group w-full flex items-center cursor-pointer gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50"
                        :class="{
                            'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm':
                                isActive(item),
                        }"
                    >
                        <div
                            class="w-8 h-8 flex items-center justify-center rounded-xl bg-gray-200 dark:bg-gray-700 transition-all duration-200 group-hover:scale-110 group-hover:bg-gray-300 dark:group-hover:bg-gray-600"
                        >
                            <i
                                :class="[
                                    'mdi',
                                    item.icon,
                                    'text-gray-600 dark:text-gray-300 text-base',
                                ]"
                            ></i>
                        </div>
                        <span class="font-medium">{{ __(item.name) }}</span>
                    </button>
                </div>
            </div>

            <!-- Policies Section -->
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                <h3
                    class="text-xs font-semibold flex items-center text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3"
                >
                    <span class="mdi mdi-file-sign text-2xl me-2"></span>
                    {{ __("Policies & Legal") }}
                </h3>
                <div class="space-y-1">
                    <button
                        v-for="(item, index) in page.props.policies"
                        :key="index"
                        @click="open(item)"
                        v-show="item.show"
                        class="group w-full flex items-center cursor-pointer gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50"
                        :class="{
                            'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm':
                                isActive(item),
                        }"
                    >
                        <div
                            class="w-8 h-8 flex items-center justify-center rounded-xl bg-gray-200 dark:bg-gray-700 transition-all duration-200 group-hover:scale-110 group-hover:bg-gray-300 dark:group-hover:bg-gray-600"
                        >
                            <i
                                :class="[
                                    'mdi',
                                    item.icon,
                                    'text-gray-600 dark:text-gray-300 text-base',
                                ]"
                            ></i>
                        </div>
                        <span class="font-medium">{{ __(item.name) }}</span>
                    </button>
                </div>
            </div>

            <div
                class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700"
            >
                <h3
                    class="text-xs font-semibold flex items-center text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3"
                >
                    <span class="mdi mdi-apps text-2xl me-2"></span>
                    {{ __("My apps") }}
                </h3>
                <div class="space-y-1">
                    <button
                        v-for="(item, index) in $page.props?.user_routes"
                        :key="index"
                        @click="open(item)"
                        class="group w-full flex items-center cursor-pointer gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50"
                        :class="{
                            'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm':
                                isActive(item),
                        }"
                    >
                        <div
                            class="w-8 h-8 flex items-center justify-center rounded-xl bg-gray-200 dark:bg-gray-700 transition-all duration-200 group-hover:scale-110 group-hover:bg-gray-300 dark:group-hover:bg-gray-600"
                        >
                            <i
                                :class="[
                                    'mdi',
                                    item.icon,
                                    'text-gray-600 dark:text-gray-300 text-base',
                                ]"
                            ></i>
                        </div>
                        <span class="font-medium">{{ __(item.name) }}</span>
                    </button>
                </div>
            </div>

            <div
                class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700"
            >
                <h3
                    class="text-xs flex items-center font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3"
                >
                    <span
                        class="mdi mdi-account-lock-outline text-2xl me-2"
                    ></span>
                    {{ __("User Settings") }}
                </h3>
                <div class="space-y-1">
                    <button
                        v-for="(item, index) in $page.props?.user_settings"
                        :key="index"
                        @click="open(item)"
                        class="group w-full flex items-center cursor-pointer gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50"
                        :class="{
                            'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm':
                                isActive(item),
                        }"
                    >
                        <div
                            class="w-8 h-8 flex items-center justify-center rounded-xl bg-gray-200 dark:bg-gray-700 transition-all duration-200 group-hover:scale-110 group-hover:bg-gray-300 dark:group-hover:bg-gray-600"
                        >
                            <i
                                :class="[
                                    'mdi',
                                    item.icon,
                                    'text-gray-600 dark:text-gray-300 text-base',
                                ]"
                            ></i>
                        </div>
                        <span class="font-medium">{{ __(item.name) }}</span>
                    </button>
                </div>
            </div>
        </template>
        <template #main>
            <slot />
        </template>
    </v-layout>
</template>

<script setup>
import VLayout from "@/components/VLayout.vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();

const open = (item) => {
    window.location.href = item.route;
};

const isActive = (item) => {
    if (!item?.route) return false;

    // Current query without params
    const currentPath = window.location.pathname;

    //Get only the path without query params
    const itemPath = new URL(item.route, window.location.origin).pathname;

    return currentPath === itemPath;
};
</script>
