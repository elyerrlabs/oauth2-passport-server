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
    <div class="q-ma-sm">
        <q-btn
            no-caps
            dense
            outline
            round
            color="primary"
            icon="mdi-bell"
            class="text-white"
        >
            <q-badge
                v-if="unread_notification.length"
                color="red"
                floating
                rounded
            >
                {{ unread_notification.length }}
            </q-badge>

            <q-menu fit anchor="bottom right" self="top right">
                <q-card style="min-width: 320px; max-width: 400px">
                    <q-card-section>
                        <div class="text-h6">{{ __("Notifications") }}</div>
                    </q-card-section>

                    <q-separator />

                    <q-list separator v-if="unread_notification.length">
                        <q-item
                            v-for="n in unread_notification"
                            :key="n.id"
                            clickable
                            v-ripple
                            @click="markAsRead(n)"
                        >
                            <q-item-section avatar>
                                <q-avatar color="primary" text-color="white">
                                    <q-icon name="mdi-email-alert" />
                                </q-avatar>
                            </q-item-section>

                            <q-item-section>
                                <q-item-label class="text-weight-bold">
                                    {{ n.title }}
                                </q-item-label>
                                <q-item-label caption>{{
                                    n.message
                                }}</q-item-label>
                            </q-item-section>

                            <q-item-section side top>
                                <q-icon
                                    name="mdi-clock-outline"
                                    size="16px"
                                    class="q-mr-xs"
                                />
                                <span class="text-caption">{{
                                    n.created
                                }}</span>
                            </q-item-section>
                        </q-item>
                    </q-list>

                    <div v-else class="text-center q-pa-md">
                        <q-icon
                            name="mdi-email-check"
                            size="40px"
                            color="green"
                        />
                        <div class="text-subtitle2 q-mt-sm">
                            {{ __("All caught up!") }}
                        </div>
                    </div>

                    <q-separator />

                    <!-- Footer -->
                    <q-card-actions align="right">
                        <q-btn
                            flat
                            color="primary"
                            icon="mdi-check-all"
                            :label="__('Mark as read')"
                            :disable="!unread_notification.length"
                            @click="markAllAsRead"
                        />
                    </q-card-actions>
                </q-card>
            </q-menu>
        </q-btn>
    </div>
</template>

<script>
export default {
    data() {
        return {
            unread_notification: [],
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
                    this.unread_notification = res.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
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
                    this.$q.notify({
                        message: this.__("Notification marked as read"),
                        color: "positive",
                        icon: "mdi-check",
                        position: "top-right",
                    });
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
                }
            }
        },

        async markAllAsRead() {
            try {
                for (const n of this.unread_notification) {
                    await this.$server.post(
                        this.$page.props.notification_mark_as_read.route
                    );
                }
                this.getUnreadNotifications();
                this.$q.notify({
                    message: this.__("All notification mark as read"),
                    color: "positive",
                    icon: "mdi-check-all",
                    position: "top-right",
                });
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
                }
            }
        },
    },
};
</script>
