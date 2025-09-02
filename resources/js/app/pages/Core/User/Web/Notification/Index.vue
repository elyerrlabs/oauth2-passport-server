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
-->
<template>
    <v-user-layout>
        <q-page class="notifications-page">
            <div class="page-container">
                <!-- Header Section -->
                <div class="page-header">
                    <div class="header-content">
                        <q-icon
                            name="mdi-bell"
                            size="36px"
                            color="primary"
                            class="header-icon"
                        />
                        <q-toolbar-title
                            class="text-h4 text-weight-bold text-grey-8"
                        >
                            {{ __("Notifications") }}
                        </q-toolbar-title>
                    </div>
                    <div class="text-subtitle1 text-grey-7 q-mt-sm">
                        {{
                            __(
                                "Stay updated with your latest activities and alerts"
                            )
                        }}
                    </div>
                </div>

                <!-- Notifications Card -->
                <q-card class="notifications-card" flat bordered>
                    <!-- Tabs -->
                    <q-tabs
                        v-model="tab"
                        dense
                        class="text-primary tabs-header"
                        active-color="primary"
                        indicator-color="primary"
                        align="left"
                        narrow-indicator
                    >
                        <q-tab
                            name="all"
                            :label="__('All Notifications')"
                            icon="mdi-email"
                        />
                        <q-tab name="unread" class="unread-tab">
                            <div class="tab-content">
                                <q-icon
                                    name="mdi-email-alert"
                                    class="q-mr-xs"
                                />
                                {{ __("Unread") }}
                                <q-badge
                                    color="red"
                                    floating
                                    rounded
                                    class="unread-badge"
                                >
                                    {{ unread_notification.length }}
                                </q-badge>
                            </div>
                        </q-tab>
                    </q-tabs>

                    <q-separator />

                    <!-- Tab Panels -->
                    <q-tab-panels v-model="tab" animated class="tab-panels">
                        <!-- All Notifications -->
                        <q-tab-panel name="all" class="tab-panel">
                            <template v-if="notifications.length">
                                <q-list separator class="notification-list">
                                    <q-item
                                        v-for="notification in notifications"
                                        :key="notification.id"
                                        clickable
                                        @click="markAsRead(notification)"
                                        class="notification-item"
                                        :class="{
                                            'unread-item':
                                                !notification.read_at,
                                        }"
                                    >
                                        <q-item-section avatar>
                                            <q-avatar
                                                size="48px"
                                                :color="
                                                    notification.read_at
                                                        ? 'grey-3'
                                                        : 'primary'
                                                "
                                                text-color="white"
                                                class="notification-avatar"
                                            >
                                                <q-icon
                                                    :name="
                                                        notification.read_at
                                                            ? 'mdi-email-open'
                                                            : 'mdi-email'
                                                    "
                                                    size="24px"
                                                />
                                            </q-avatar>
                                        </q-item-section>

                                        <q-item-section
                                            class="notification-content"
                                        >
                                            <q-item-label
                                                class="notification-title text-weight-medium"
                                            >
                                                {{ notification.title }}
                                            </q-item-label>
                                            <q-item-label
                                                class="notification-message text-grey-7"
                                            >
                                                {{ notification.message }}
                                            </q-item-label>
                                            <q-item-label
                                                class="notification-time text-caption text-grey-5"
                                            >
                                                <q-icon
                                                    name="mdi-clock-outline"
                                                    size="14px"
                                                    class="q-mr-xs"
                                                />
                                                {{ notification.created }}
                                            </q-item-label>
                                        </q-item-section>

                                        <q-item-section side top>
                                            <div class="notification-actions">
                                                <q-btn
                                                    flat
                                                    round
                                                    dense
                                                    icon="mdi-close"
                                                    size="sm"
                                                    color="grey-6"
                                                    @click.stop="
                                                        markAsRead(notification)
                                                    "
                                                    class="action-btn"
                                                >
                                                    <q-tooltip>{{
                                                        __("Mark as read")
                                                    }}</q-tooltip>
                                                </q-btn>
                                                <div
                                                    v-if="!notification.read_at"
                                                    class="unread-dot bg-primary"
                                                ></div>
                                            </div>
                                        </q-item-section>
                                    </q-item>
                                </q-list>
                            </template>

                            <template v-else>
                                <div class="empty-state text-center q-pa-xl">
                                    <q-icon
                                        name="mdi-inbox"
                                        size="64px"
                                        color="grey-4"
                                        class="empty-icon"
                                    />
                                    <div
                                        class="empty-title text-h6 text-grey-7 q-mt-md"
                                    >
                                        {{ __("No notifications yet") }}
                                    </div>
                                    <div class="empty-subtitle text-grey-5">
                                        {{
                                            __(
                                                "Your notifications will appear here"
                                            )
                                        }}
                                    </div>
                                </div>
                            </template>
                        </q-tab-panel>

                        <!-- Unread Notifications -->
                        <q-tab-panel name="unread" class="tab-panel">
                            <template v-if="unread_notification.length">
                                <q-list separator class="notification-list">
                                    <q-item
                                        v-for="notification in unread_notification"
                                        :key="notification.id"
                                        clickable
                                        @click="markAsRead(notification)"
                                        class="notification-item unread-item"
                                    >
                                        <q-item-section avatar>
                                            <q-avatar
                                                size="48px"
                                                color="primary"
                                                text-color="white"
                                                class="notification-avatar"
                                            >
                                                <q-icon
                                                    name="mdi-email-alert"
                                                    size="24px"
                                                />
                                            </q-avatar>
                                        </q-item-section>

                                        <q-item-section
                                            class="notification-content"
                                        >
                                            <q-item-label
                                                class="notification-title text-weight-bold"
                                            >
                                                {{ notification.title }}
                                            </q-item-label>
                                            <q-item-label
                                                class="notification-message text-grey-7"
                                            >
                                                {{ notification.message }}
                                            </q-item-label>
                                            <q-item-label
                                                class="notification-time text-caption text-grey-5"
                                            >
                                                <q-icon
                                                    name="mdi-clock-outline"
                                                    size="14px"
                                                    class="q-mr-xs"
                                                />
                                                {{ notification.created }}
                                            </q-item-label>
                                        </q-item-section>

                                        <q-item-section side top>
                                            <div class="notification-actions">
                                                <q-btn
                                                    flat
                                                    round
                                                    dense
                                                    icon="mdi-check"
                                                    size="sm"
                                                    color="primary"
                                                    @click.stop="
                                                        markAsRead(notification)
                                                    "
                                                    class="action-btn"
                                                >
                                                    <q-tooltip>{{
                                                        __("Mark as read")
                                                    }}</q-tooltip>
                                                </q-btn>
                                                <div
                                                    class="unread-dot bg-primary"
                                                ></div>
                                            </div>
                                        </q-item-section>
                                    </q-item>
                                </q-list>
                            </template>

                            <template v-else>
                                <div class="empty-state text-center q-pa-xl">
                                    <q-icon
                                        name="mdi-email-check"
                                        size="64px"
                                        color="green-4"
                                        class="empty-icon"
                                    />
                                    <div
                                        class="empty-title text-h6 text-green-7 q-mt-md"
                                    >
                                        {{ __("All caught up!") }}
                                    </div>
                                    <div class="empty-subtitle text-grey-5">
                                        {{ __("No unread notifications") }}
                                    </div>
                                </div>
                            </template>
                        </q-tab-panel>
                    </q-tab-panels>

                    <!-- Actions Footer -->
                    <q-card-actions
                        v-if="notifications.length"
                        class="card-actions"
                    >
                        <q-btn
                            :label="__('Mark all as read')"
                            color="primary"
                            outline
                            icon="mdi-check-all"
                            @click="markAllAsRead"
                            :disable="unread_notification.length === 0"
                            class="mark-all-btn"
                        />
                        <q-space />
                        <q-btn
                            :label="__('Refresh')"
                            color="grey-6"
                            flat
                            icon="mdi-refresh"
                            @click="refreshNotifications"
                            class="refresh-btn"
                        />
                    </q-card-actions>
                </q-card>
            </div>
        </q-page>
    </v-user-layout>
</template>

<script>
export default {
    data() {
        return {
            notifications: [],
            unread_notification: [],
            tab: "unread",
            loading: false,
        };
    },

    mounted() {
        this.getNotifications();
        this.getUnreadNotifications();
    },

    methods: {
        async getNotifications() {
            this.loading = true;
            try {
                const res = await this.$server.get(
                    this.$page.props.route["all"]
                );
                if (res.status === 200) {
                    this.notifications = res.data.data;
                }
            } catch (error) {
                console.error("Failed to load notifications", error);
            } finally {
                this.loading = false;
            }
        },

        openLink(url) {
            if (url) {
                window.open(url, "_blank");
            }
        },

        async markAsRead(notification) {
            try {
                const res = await this.$server.post(
                    notification.links.mark_as_read
                );

                if (res.status == 201) {
                    this.getUnreadNotifications();
                    this.getNotifications();
                    this.openLink(notification.link);

                    this.$q.notify({
                        message: "Notification marked as read",
                        color: "positive",
                        icon: "mdi-check",
                        position: "top-right",
                        timeout: 2000,
                    });
                }
            } catch (error) {
                console.error("Failed to mark notification as read", error);
            }
        },

        async markAllAsRead() {
            try {
                // Implement mark all as read functionality
                // This would typically call a bulk update endpoint
                for (const notification of this.unread_notification) {
                    await this.$server.post(notification.links.mark_as_read);
                }

                this.getUnreadNotifications();
                this.getNotifications();

                this.$q.notify({
                    message: "All notifications marked as read",
                    color: "positive",
                    icon: "mdi-check-all",
                    position: "top-right",
                    timeout: 2000,
                });
            } catch (error) {
                console.error("Failed to mark all as read", error);
            }
        },

        async getUnreadNotifications() {
            try {
                const res = await this.$server.get(
                    this.$page.props.route["unread"]
                );
                if (res.status === 200) {
                    this.unread_notification = res.data.data;
                }
            } catch (error) {
                console.error("Failed to load unread notifications", error);
            }
        },

        refreshNotifications() {
            this.getNotifications();
            this.getUnreadNotifications();

            this.$q.notify({
                message: "Notifications refreshed",
                color: "info",
                icon: "mdi-refresh",
                position: "top-right",
                timeout: 1500,
            });
        },
    },
};
</script>

<style lang="scss" scoped>
.notifications-page {
    //   background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
    min-height: 100vh;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding: 24px;
}

.page-container {
    max-width: 900px;
    width: 100%;
}

.page-header {
    text-align: center;
    margin-bottom: 32px;

    .header-content {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        margin-bottom: 8px;
    }

    .header-icon {
        background: rgba(0, 0, 0, 0.05);
        padding: 16px;
        border-radius: 50%;
    }
}

.notifications-card {
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;

    .tabs-header {
        padding: 16px 24px 0;

        .unread-tab {
            position: relative;

            .tab-content {
                display: flex;
                align-items: center;
            }

            .unread-badge {
                position: absolute;
                top: -8px;
                right: -8px;
                font-size: 10px;
                padding: 2px 6px;
            }
        }
    }

    .tab-panels {
        min-height: 400px;

        .tab-panel {
            padding: 0;
        }
    }
}

.notification-list {
    padding: 8px;
}

.notification-item {
    border-radius: 12px;
    margin: 8px;
    padding: 16px;
    transition: all 0.3s ease;
    border-left: 4px solid transparent;

    &:hover {
        background: rgba(0, 0, 0, 0.02);
        transform: translateX(4px);
        border-left-color: var(--q-primary);
    }

    &.unread-item {
        background: rgba(0, 123, 255, 0.05);
        border-left-color: var(--q-primary);

        &:hover {
            background: rgba(0, 123, 255, 0.08);
        }
    }

    .notification-avatar {
        transition: transform 0.3s ease;
    }

    &:hover .notification-avatar {
        transform: scale(1.1);
    }

    .notification-content {
        .notification-title {
            font-size: 1rem;
            margin-bottom: 4px;
        }

        .notification-message {
            font-size: 0.9rem;
            line-height: 1.4;
            margin-bottom: 8px;
        }

        .notification-time {
            display: flex;
            align-items: center;
        }
    }

    .notification-actions {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;

        .action-btn {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .unread-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }
    }

    &:hover .action-btn {
        opacity: 1;
    }
}

.empty-state {
    .empty-icon {
        opacity: 0.5;
    }

    .empty-title {
        font-weight: 500;
    }

    .empty-subtitle {
        font-size: 0.9rem;
    }
}

.card-actions {
    padding: 16px 24px;
    border-top: 1px solid rgba(0, 0, 0, 0.06);

    .mark-all-btn,
    .refresh-btn {
        border-radius: 8px;
        font-weight: 500;
    }
}

// Responsive adjustments
@media (max-width: 1023px) {
    .notifications-page {
        padding: 16px;
    }

    .page-header {
        .text-h4 {
            font-size: 1.75rem;
        }

        .header-icon {
            padding: 12px;
            font-size: 28px;
        }
    }
}

@media (max-width: 599px) {
    .notifications-page {
        padding: 12px;
    }

    .page-header {
        .text-h4 {
            font-size: 1.5rem;
        }

        .header-content {
            flex-direction: column;
            gap: 12px;
        }
    }

    .notification-item {
        padding: 12px;

        .notification-avatar {
            width: 40px;
            height: 40px;
            min-width: 40px;

            .q-icon {
                font-size: 20px;
            }
        }

        .notification-actions .action-btn {
            opacity: 1; // Always show on mobile
        }
    }

    .card-actions {
        flex-direction: column;
        gap: 12px;

        .q-btn {
            width: 100%;
        }
    }
}
</style>
