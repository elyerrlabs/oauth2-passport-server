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
            <div v-if="developers.show">
                <h3
                    class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-6"
                >
                    {{ __(developers.name) }}
                </h3>
                <button
                    v-for="(item, index) in developers.menu"
                    :key="index"
                    @click="open(item)"
                    class="w-full flex items-center space-x-3 px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200"
                >
                    <div
                        class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center"
                    >
                        <i
                            :class="['mdi', item.icon, 'text-white text-sm']"
                        ></i>
                    </div>
                    <span>{{ __(item.name) }}</span>
                </button>
            </div>

            <!-- Dashboards Section -->
            <div v-if="admin_dashboard.length">
                <h3
                    class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-6"
                >
                    {{ __("Dashboards") }}
                </h3>
                <button
                    v-for="(item, index) in admin_dashboard"
                    :key="index"
                    @click="open(item)"
                    class="w-full flex items-center space-x-3 px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200"
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

            <!-- Policies Section -->
            <div class="pt-4 border-t border-gray-200">
                <button
                    v-for="(item, index) in $page.props.policies"
                    :key="index"
                    @click="open(item)"
                    v-show="item.show"
                    class="w-full flex items-center space-x-3 px-3 py-2 text-sm font-medium text-gray-500 rounded-lg hover:bg-gray-100 hover:text-gray-700 transition-colors duration-200"
                >
                    <div
                        class="w-8 h-8 bg-gray-400 rounded-lg flex items-center justify-center"
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
import VNotification from "@/components/VNotification.vue";
import VProfile from "@/components/VProfile.vue";
import VLayout from "@/components/VLayout.vue";

export default {
    components: {
        VLayout,
        VProfile,
        VNotification,
    },

    data() {
        return {
            user: {},
            menus: [],
            admin_dashboard: [],
            transaction_dashboard: [],
            partner_dashboard: [],
            settings: {},
            developers: [],
            ecommerce_dashboard: [],
        };
    },

    computed: {
        userInitials() {
            if (!this.user || !this.user.name) return "U";
            return this.user.name
                .split(" ")
                .map((n) => n[0])
                .join("")
                .toUpperCase();
        },
    },

    created() {
        this.menus = this.$page.props.user_routes ?? [];
        this.admin_dashboard = this.$page.props.admin_dashboard ?? [];
        this.developers = this.$page.props.developers ?? [];
    },

    methods: {
        open(item) {
            window.location.href = item.route;
        },
    },
};
</script>

<style scoped>
/* Custom scrollbar for sidebar */
.aside-scrollbar::-webkit-scrollbar {
    width: 4px;
}

.aside-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.aside-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.aside-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
