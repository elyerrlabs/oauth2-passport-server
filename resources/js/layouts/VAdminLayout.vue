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
            <ul class="sidebar-menu space-y-1">
                <li v-for="(item, index) in menus" :key="index">
                    <a
                        @click="open(item)"
                        class=""
                        :class="[
                            'menu-item flex items-center px-4 py-3 cursor-pointer transition-colors',
                            isActive(item)
                                ? 'menu-item-active bg-primary-50 text-primary-600 border-r-2 border-primary-600'
                                : 'text-gray-700 hover:bg-gray-100',
                        ]"
                    >
                        <div
                            class="menu-icon w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center text-white mr-3"
                        >
                            <i
                                :class="item.icon"
                                class="mdi text-blue-600 text-2xl"
                            ></i>
                        </div>

                        <span class="menu-text flex-1 font-medium">
                            {{ __(item.name) }}
                        </span>

                        <div v-if="isActive(item)" class="ml-2">
                            <svg
                                class="w-5 h-5 text-primary-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                ></path>
                            </svg>
                        </div>
                    </a>
                    <div
                        class="menu-separator border-t border-gray-100 my-1"
                    ></div>
                </li>
            </ul>
        </template>
        <template #main>
            <slot />
        </template>
    </v-layout>
</template>

<script>
import VLayout from "@/components/VLayout.vue";

export default {
    components: {
        VLayout,
    },
    data() {
        return {
            errors: {},
            menus: [],
        };
    },

    created() {
        this.menus = this.$page.props.admin_routes;
    },

    methods: {
        open(item) {
            window.location.href = item.route;
        },

        isActive(item) {
            return item.route == window.location.href;
        },
    },
};
</script>
