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
            <!-- Developers Section -->
            <div v-if="developers.show" class="mb-6">
                <h3
                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3 mt-2"
                >
                    {{ __(developers.name) }}
                </h3>
                <div class="space-y-1">
                    <button
                        v-for="(item, index) in developers.menu"
                        :key="index"
                        @click="open(item)"
                        class="w-full flex items-center cursor-pointer space-x-3 px-3 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300 transition-all duration-200 group"
                    >
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 rounded-lg flex items-center justify-center shadow-xs group-hover:scale-105 transition-transform duration-200"
                        >
                            <i
                                :class="[
                                    'mdi',
                                    item.icon,
                                    'text-white text-sm',
                                ]"
                            ></i>
                        </div>
                        <span class="font-medium">{{ __(item.name) }}</span>
                    </button>
                </div>
            </div>

            <!-- Dashboards Section -->
            <div v-if="admin_dashboard.length" class="mb-6">
                <h3
                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3"
                >
                    {{ __("Dashboards") }}
                </h3>
                <div class="space-y-1">
                    <button
                        v-for="(item, index) in admin_dashboard"
                        :key="index"
                        @click="open(item)"
                        class="w-full flex items-center cursor-pointer space-x-3 px-3 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:text-emerald-700 dark:hover:text-emerald-300 transition-all duration-200 group"
                    >
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 dark:from-emerald-600 dark:to-emerald-700 rounded-lg flex items-center justify-center shadow-xs group-hover:scale-105 transition-transform duration-200"
                        >
                            <i
                                :class="[
                                    'mdi',
                                    item.icon,
                                    'text-white text-sm',
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
                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3"
                >
                    {{ __("Policies & Legal") }}
                </h3>
                <div class="space-y-1">
                    <button
                        v-for="(item, index) in page.props.policies"
                        :key="index"
                        @click="open(item)"
                        v-show="item.show"
                        class="w-full flex items-center cursor-pointer space-x-3 px-3 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white transition-all duration-200 group"
                    >
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-gray-500 to-gray-600 dark:from-gray-600 dark:to-gray-700 rounded-lg flex items-center justify-center shadow-xs group-hover:scale-105 transition-transform duration-200"
                        >
                            <i
                                :class="[
                                    'mdi',
                                    item.icon,
                                    'text-white text-sm',
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
import { usePage, router } from "@inertiajs/vue3";
import { ref, computed, onMounted } from "vue";

const page = usePage();
const menus = ref([]);
const admin_dashboard = ref([]);
const developers = ref([]);

onMounted(() => {
    menus.value = page.props.user_routes ?? [];
    admin_dashboard.value = page.props.admin_dashboard ?? [];
    developers.value = page.props.developers ?? [];
});

const open = (item) => {
    router.visit(item.route);
};
</script>
