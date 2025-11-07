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
            class="px-2 py-1 text-gray-200 bg-purple-700 rounded-full hover:text-white relative cursor-pointer"
        >
            <i class="mdi mdi-bell lg:text-xl bg-secondary"></i>
            <!-- Badge -->

            <span
                v-if="unreadNotifications.length"
                class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full"
            >
                {{ unreadNotifications.length }}
            </span>
        </button>

        <!-- Dropdown -->
        <div
            v-show="dropdownOpen"
            @click.outside="dropdownOpen = false"
            class="origin-top-right absolute right-0 mt-1 w-80 bg-white border border-gray-200 divide-y divide-gray-200 rounded-lg shadow-lg z-50"
        >
            <!-- Header -->
            <div class="px-2 py-2">
                <h3 class="text-lg font-semibold">Notifications</h3>
            </div>

            <!-- Notifications List -->
            <div class="max-h-60 overflow-y-auto">
                <div v-if="unreadNotifications.length">
                    <div
                        v-for="n in unreadNotifications"
                        :key="n.id"
                        @click="markAsRead(n)"
                        class="flex items-start px-4 py-3 hover:bg-gray-100 cursor-pointer"
                    >
                        <div class="flex-shrink-0">
                            <div
                                class="bg-purple-600 text-white rounded-full p-2 flex items-center justify-center"
                            >
                                <i class="mdi mdi-email-alert"></i>
                            </div>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-semibold">{{ n.title }}</p>
                            <p class="text-xs text-gray-500">{{ n.message }}</p>
                        </div>
                        <div class="flex-shrink-0 text-xs text-gray-400 ml-2">
                            <i class="mdi mdi-clock-outline mr-1"></i
                            >{{ n.created }}
                        </div>
                    </div>
                </div>

                <div v-else>
                    <div
                        class="flex flex-col items-center justify-center py-8 text-gray-500"
                    >
                        <i
                            class="mdi mdi-email-check lg:text-4xl text-green-500"
                        ></i>
                        <span class="mt-2 text-sm font-medium">
                            All caught up!
                        </span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-4 py-2 flex justify-end">
                <button
                    @click="markAllAsRead"
                    :disabled="!unreadNotifications.length"
                    class="px-3 py-1 rounded text-white bg-purple-600 hover:bg-purple-700 disabled:opacity-50 disabled:cursor-not-allowed transition"
                >
                    Mark all as read
                </button>
            </div>
        </div>
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
                     $notify.error(e.response.data.message);
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
                     $notify.success(
                        __("Notification marked as read")
                    );
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                     $notify.error(e.response.data.message);
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
                if (e?.response?.data?.message) {
                     $notify.success(
                        __("All notifications marked as read")
                    );
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                     $notify.error(e.response.data.message);
                }
            }
        },
    },
};
</script>
