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
        <!-- Notification Button -->
        <button
            @click="dropdownOpen = !dropdownOpen"
            class="relative w-8 h-8 text-green-600 dark:text-green-400 hover:text-green-400 dark:hover:text-white bg-white dark:bg-gray-800 rounded-full hover:bg-white dark:hover:bg-gray-700 transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
        >
            <i class="mdi mdi-bell text-lg"></i>

            <!-- Badge -->
            <span
                v-if="unreadNotifications.length"
                class="absolute -top-1 -right-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold leading-none text-white bg-red-500 rounded-full transform scale-100 transition-transform duration-200"
                :class="{ 'animate-pulse': unreadNotifications.length > 0 }"
            >
                {{
                    unreadNotifications.length > 99
                        ? "99+"
                        : unreadNotifications.length
                }}
            </span>
        </button>

        <!-- Dropdown -->
        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div
                v-show="dropdownOpen"
                @click.outside="dropdownOpen = false"
                class="origin-top-right absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl z-50 overflow-hidden"
            >
                <!-- Header -->
                <div
                    class="px-4 py-3 bg-linear-to-r from-blue-600 to-purple-600 dark:from-blue-700 dark:to-purple-700"
                >
                    <div class="flex items-center justify-between">
                        <h3
                            class="text-lg font-semibold text-white flex items-center"
                        >
                            <i class="mdi mdi-bell-outline mr-2"></i>
                            {{ __("Notifications") }}
                        </h3>
                        <span class="text-blue-100 text-sm font-medium">
                            {{ unreadNotifications.length }} {{ __("unread") }}
                        </span>
                    </div>
                </div>

                <!-- Notifications List -->
                <div class="max-h-80 overflow-y-auto">
                    <div v-if="unreadNotifications.length">
                        <div
                            v-for="n in unreadNotifications"
                            :key="n.id"
                            @click="markAsRead(n)"
                            class="flex items-start p-4 border-b border-gray-100 dark:border-gray-700 hover:bg-white dark:hover:bg-gray-600 cursor-pointer transition-colors duration-150 group"
                        >
                            <div class="shrink-0 mt-1">
                                <div
                                    class="w-10 h-10 bg-linear-to-br from-blue-500 to-purple-500 text-white rounded-xl flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow duration-200"
                                >
                                    <i class="mdi mdi-email-alert text-lg"></i>
                                </div>
                            </div>
                            <div class="ml-3 flex-1 min-w-0">
                                <p
                                    class="text-sm font-semibold text-gray-900 dark:text-white truncate"
                                >
                                    {{ __(n.title) }}
                                </p>
                                <p
                                    class="text-xs text-gray-600 dark:text-gray-400 mt-1 line-clamp-2"
                                >
                                    {{ __(n.message) }}
                                </p>
                                <div
                                    class="flex items-center mt-2 text-xs text-gray-500 dark:text-gray-500"
                                >
                                    <i class="mdi mdi-clock-outline mr-1"></i>
                                    <span>{{ n.created }}</span>
                                </div>
                            </div>
                            <div class="shrink-0 ml-2">
                                <div
                                    class="w-2 h-2 bg-blue-500 rounded-full group-hover:bg-green-500 transition-colors duration-200"
                                ></div>
                            </div>
                        </div>
                    </div>

                    <div v-else>
                        <div
                            class="flex flex-col items-center justify-center py-12 text-gray-400 dark:text-gray-500"
                        >
                            <div
                                class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-3"
                            >
                                <i
                                    class="mdi mdi-email-check text-2xl text-green-400"
                                ></i>
                            </div>
                            <span
                                class="text-sm font-medium text-gray-500 dark:text-gray-400"
                            >
                                {{ __("All caught up!") }}
                            </span>
                            <span
                                class="text-xs text-gray-400 dark:text-gray-500 mt-1"
                            >
                                {{ __("No new notifications") }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div
                    v-if="unreadNotifications.length"
                    class="px-4 py-3 bg-white dark:bg-gray-700 border-t border-gray-200 dark:border-gray-700"
                >
                    <div class="flex justify-between items-center">
                        <button
                            @click="markAllAsRead"
                            :disabled="!unreadNotifications.length"
                            class="px-4 py-2 text-sm font-medium text-white bg-linear-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 disabled:opacity-50 disabled:cursor-not-allowed rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                        >
                            <i class="mdi mdi-check-all mr-1"></i>
                            {{ __("Mark all as read") }}
                        </button>
                        <button
                            @click="dropdownOpen = false"
                            class="px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors duration-200"
                        >
                            {{ __("Close") }}
                        </button>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    data() {
        return {
            unreadNotifications: [],
            dropdownOpen: false,
        };
    },
    mounted() {
        if (this.$page.props.user?.id) {
            this.getUnreadNotifications();
        }
    },
    methods: {
        async getUnreadNotifications() {
            try {
                const res = await this.$server.get(
                    this.$page.props.notification.route
                );
                if (res.status === 200) {
                    this.unreadNotifications = res.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$notify?.error(e.response.data.message);
                }
            }
        },
        async markAsRead(notification) {
            try {
                const res = await this.$server.post(
                    notification.links.mark_as_read
                );
                if (res.status === 201) {
                    this.getUnreadNotifications();
                    if (notification.link) {
                        window.open(notification.link, "_blank");
                    }
                    this.$notify?.success(__("Notification marked as read"));
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$notify?.error(e.response.data.message);
                }
            }
        },

        async markAllAsRead() {
            try {
                for (const n of this.unreadNotifications) {
                    await this.$server.post(
                        this.$page.props.notification_mark_as_read.route
                    );
                }
                this.getUnreadNotifications();
                this.$notify?.success(__("All notifications marked as read"));
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$notify?.error(e.response.data.message);
                }
            }
        },
    },
};
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.gray-750 {
    background-color: #374151;
}
</style>
