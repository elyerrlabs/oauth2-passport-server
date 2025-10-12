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
            <div>
                <h3
                    class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-6"
                >
                    {{ __("Navigation") }}
                </h3>
                <button
                    v-for="(item, index) in menus"
                    :key="index"
                    @click="open(item)"
                    class="w-full flex items-center space-x-3 px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200"
                    :class="{ 'bg-gray-300': isActive(item) }"
                >
                    <div
                        class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center"
                    >
                        <i
                            :class="['mdi', item.icon, 'text-white text-sm']"
                        ></i>
                    </div>
                    <span>{{ __(item.name) }}</span>
                </button>
            </div>
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
            window.location.href = item.route;
        },

        isActive(item) {
            return item.route == window.location.href;
        },
    },
};
</script>
