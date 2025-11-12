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
            <div
                class="h-full bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700"
            >
                <h3
                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 mt-6 px-3"
                >
                    {{ __("Navigation") }}
                </h3>
                <div class="space-y-1 px-2">
                    <button
                        v-for="(item, index) in menus"
                        :key="index"
                        @click="open(item)"
                        class="w-full flex items-center space-x-3 px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200"
                        :class="{
                            'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800':
                                isActive(item),
                            'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white':
                                !isActive(item),
                        }"
                    >
                        <div
                            class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors duration-200"
                            :class="{
                                'bg-blue-600 dark:bg-blue-500': isActive(item),
                                'bg-green-600 dark:bg-green-500':
                                    !isActive(item),
                            }"
                        >
                            <i
                                :class="[
                                    'mdi',
                                    item.icon,
                                    'text-white text-sm',
                                ]"
                            ></i>
                        </div>
                        <span class="text-left">{{ __(item.name) }}</span>
                    </button>
                </div>
            </div>
        </template>
        <template #main>
            <slot />
        </template>
    </v-layout>
</template>

<script>
import VLayout from "@/components/VLayout.vue";
import { router } from "@inertiajs/vue3";
export default {
    components: {
        VLayout,
    },
    data() {
        return {
            menus: [],
        };
    },

    created() {
        this.menus = this.$page.props.transaction_routes;
    },

    methods: {
        toggleLeftDrawer() {
            this.leftDrawerOpen = !this.leftDrawerOpen;
        },

        open(item) {
            router.visit(item.route);
        },

        isActive(item) {
            return (
                item.route ==
                `${window.location.origin}${window.location.pathname}`
            );
        },
    },
};
</script>
