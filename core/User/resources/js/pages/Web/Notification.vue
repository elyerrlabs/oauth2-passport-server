<!--
OAuth2 Passport Server — a centralized, modular authorization server
implementing OAuth 2.0 and OpenID Connect specifications.

Copyright (c) 2026 Elvis Yerel Roman Concha

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program. If not, see <https://www.gnu.org/licenses/>.

Author: Elvis Yerel Roman Concha
Contact: yerel9212@yahoo.es

SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
    <v-main-layout>
        <div class="mx-auto max-w-7xl space-y-6 px-4 py-4 sm:px-6">
            <section
                class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800"
            >
                <div
                    class="bg-gradient-to-r from-sky-600 via-blue-600 to-indigo-700 px-6 py-8 text-white"
                >
                    <div
                        class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between"
                    >
                        <div class="max-w-2xl">
                            <div
                                class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15 backdrop-blur-sm"
                            >
                                <i
                                    class="mdi mdi-bell-badge-outline text-2xl"
                                ></i>
                            </div>
                            <h1 class="text-2xl font-bold sm:text-3xl">
                                {{ __("My Notifications") }}
                            </h1>
                            <p class="mt-2 text-sm text-sky-50 sm:text-base">
                                {{
                                    __(
                                        "Review your latest activity, unread alerts, and important account updates in one place.",
                                    )
                                }}
                            </p>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-3 lg:min-w-[420px]">
                            <div
                                class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur-sm"
                            >
                                <div
                                    class="text-xs uppercase tracking-[0.2em] text-blue-100"
                                >
                                    {{ __("Total") }}
                                </div>
                                <div class="mt-2 text-2xl font-semibold">
                                    {{ notifications.length }}
                                </div>
                            </div>
                            <div
                                class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur-sm"
                            >
                                <div
                                    class="text-xs uppercase tracking-[0.2em] text-blue-100"
                                >
                                    {{ __("Unread") }}
                                </div>
                                <div class="mt-2 text-2xl font-semibold">
                                    {{ unreadCount }}
                                </div>
                            </div>
                            <div
                                class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur-sm"
                            >
                                <div
                                    class="text-xs uppercase tracking-[0.2em] text-blue-100"
                                >
                                    {{ __("Read") }}
                                </div>
                                <div class="mt-2 text-2xl font-semibold">
                                    {{ readCount }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6 p-6 sm:p-8">
                    <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_auto]">
                        <div
                            class="grid gap-4 md:grid-cols-[minmax(0,1fr)_auto]"
                        >
                            <div class="flex flex-wrap items-end gap-2"></div>
                        </div>

                        <div class="flex flex-wrap items-end gap-2">
                            <v-button
                                @click="unread = false"
                                :loading="loading"
                                :label="__('All')"
                                left-icon="mdi mdi-refresh"
                                size="sm"
                                variant="ghost"
                            />

                            <v-button
                                @click="unread = true"
                                :loading="loading"
                                :label="__('Unread')"
                                left-icon="mdi mdi-refresh"
                                variant="primary"
                                size="sm"
                            />
                            <v-button
                                @click="markAllAsRead"
                                :disabled="!unreadCount || bulkLoading"
                                :loading="bulkLoading && bulkAction === 'read'"
                                :label="__('Mark All Read')"
                                left-icon="mdi mdi-check-all"
                                variant="success"
                                size="sm"
                            />
                            <v-button
                                @click="destroyAllNotifications"
                                :disabled="!notifications.length || bulkLoading"
                                :loading="
                                    bulkLoading && bulkAction === 'destroy'
                                "
                                :label="__('Delete All')"
                                left-icon="mdi mdi-delete-sweep-outline"
                                variant="danger"
                                size="sm"
                            />
                        </div>
                    </div>

                    <div
                        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800"
                    >
                        <v-table
                            :items="notifications"
                            :loading="loading"
                            :empty-text="__('No notifications found')"
                            empty-icon="mdi mdi-bell-off-outline"
                            loading-text="Loading notifications..."
                            table-class="min-w-[980px] w-full divide-y divide-slate-200 dark:divide-slate-700"
                            thead-class="bg-slate-50 dark:bg-slate-900/40"
                            tbody-class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700"
                        >
                            <template #head>
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300"
                                    >
                                        {{ __("Status") }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300"
                                    >
                                        {{ __("Notification") }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300"
                                    >
                                        {{ __("Date") }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-300"
                                    >
                                        {{ __("Actions") }}
                                    </th>
                                </tr>
                            </template>

                            <template #default="{ items }">
                                <tr
                                    v-for="notification in items"
                                    :key="notification.id"
                                    class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-700/40"
                                >
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-medium"
                                            :class="
                                                isUnread(notification)
                                                    ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300'
                                                    : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300'
                                            "
                                        >
                                            <span
                                                class="h-2 w-2 rounded-full"
                                                :class="
                                                    isUnread(notification)
                                                        ? 'bg-amber-500'
                                                        : 'bg-emerald-500'
                                                "
                                            ></span>
                                            {{
                                                isUnread(notification)
                                                    ? __("Unread")
                                                    : __("Read")
                                            }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="max-w-xl">
                                            <div
                                                class="font-semibold text-slate-900 dark:text-white"
                                            >
                                                {{
                                                    __(
                                                        notification.title ||
                                                            "Notification",
                                                    )
                                                }}
                                            </div>
                                            <div
                                                class="mt-1 line-clamp-2 text-sm text-slate-600 dark:text-slate-400"
                                            >
                                                {{
                                                    __(
                                                        notification.message ||
                                                            "No additional details",
                                                    )
                                                }}
                                            </div>
                                        </div>
                                    </td>

                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400"
                                    >
                                        <div>
                                            {{ notification.created || "—" }}
                                        </div>
                                        <div
                                            v-if="notification.read_at"
                                            class="mt-1 text-xs"
                                        >
                                            {{ __("Read") }}:
                                            {{ notification.read_at }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex justify-end gap-2">
                                            <v-button
                                                @click="
                                                    showNotification(
                                                        notification,
                                                    )
                                                "
                                                round
                                                size="sm"
                                                variant="light"
                                                icon="mdi mdi-eye-outline"
                                                :aria-label="
                                                    __('View notification')
                                                "
                                                :title="__('View notification')"
                                            />
                                            <v-button
                                                v-if="isUnread(notification)"
                                                @click="
                                                    markAsRead(notification)
                                                "
                                                round
                                                size="sm"
                                                variant="success"
                                                icon="mdi mdi-check"
                                                :disabled="
                                                    actionLoadingId ===
                                                    notification.id
                                                "
                                                :aria-label="__('Mark as read')"
                                                :title="__('Mark as read')"
                                            />
                                            <v-button
                                                v-if="notification.link"
                                                @click="
                                                    openLink(notification.link)
                                                "
                                                round
                                                size="sm"
                                                variant="primary"
                                                icon="mdi mdi-open-in-new"
                                                :aria-label="__('Open link')"
                                                :title="__('Open link')"
                                            />
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </v-table>
                    </div>
                </div>

                <v-paginate
                    v-model="search.page"
                    :total-pages="pages.total_pages"
                    @change="getNotifications"
                />
            </section>
        </div>

        <v-modal
            v-model="detailsOpen"
            :title="selectedNotification?.title || __('Notification Details')"
            panel-class="w-full lg:w-4xl"
        >
            <template #body>
                <div v-if="selectedNotification" class="space-y-5">
                    <div class="flex flex-wrap items-center gap-3">
                        <span
                            class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-medium"
                            :class="
                                isUnread(selectedNotification)
                                    ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300'
                                    : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300'
                            "
                        >
                            {{
                                isUnread(selectedNotification)
                                    ? __("Unread")
                                    : __("Read")
                            }}
                        </span>
                        <span
                            class="text-sm text-slate-500 dark:text-slate-400"
                        >
                            {{ selectedNotification.created || "—" }}
                        </span>
                    </div>

                    <div
                        class="rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-900/30"
                    >
                        <p
                            class="whitespace-pre-line text-sm leading-6 text-slate-700 dark:text-slate-300"
                        >
                            {{
                                __(
                                    selectedNotification.message ||
                                        "No additional details",
                                )
                            }}
                        </p>
                    </div>

                    <div class="flex flex-wrap justify-end gap-3">
                        <v-button
                            v-if="isUnread(selectedNotification)"
                            @click="markAsRead(selectedNotification, true)"
                            :disabled="
                                actionLoadingId === selectedNotification.id
                            "
                            :loading="
                                actionLoadingId === selectedNotification.id
                            "
                            :label="__('Mark as Read')"
                            left-icon="mdi mdi-check"
                            variant="success"
                        />

                        <v-button
                            v-if="selectedNotification.link"
                            @click="openLink(selectedNotification.link)"
                            :label="__('Open Link')"
                            left-icon="mdi mdi-open-in-new"
                            variant="primary"
                        />

                        <v-button
                            @click="detailsOpen = false"
                            :label="__('Close')"
                            variant="light"
                        />
                    </div>
                </div>
            </template>
        </v-modal>
    </v-main-layout>
</template>

<script setup>
import { computed, onMounted, ref, watch } from "vue";
import VMainLayout from "@/components/VMainLayout.vue";
import VButton from "@/components/VButton.vue";
import VModal from "@/components/VModal.vue";
import VTable from "@/components/VTable.vue";
import VPaginate from "@/components/VPaginate.vue";

const ROUTES = {
    index: "/system/api/user/user/notifications",
    unread: "/system/api/user/user/notifications/unread",
    markAllAsRead: "/system/api/user/user/notifications/mark-all-as-read",
    destroyAll: "/system/api/user/user/notifications/destroy-all",
};

const pages = ref({
    total_pages: 0,
});

const search = ref({
    page: 1,
    per_page: 50,
});

const unread = ref(true);
const notifications = ref([]);
const loading = ref(false);
const bulkLoading = ref(false);
const bulkAction = ref("");
const actionLoadingId = ref(null);
const detailsOpen = ref(false);
const selectedNotification = ref(null);

const unreadCount = computed(
    () => notifications.value.filter((item) => !item.read_at).length,
);
const readCount = computed(
    () => notifications.value.filter((item) => !!item.read_at).length,
);

onMounted(() => {
    getNotifications();
});

watch(
    () => unread.value,
    () => {
        getNotifications();
    },
);

function isUnread(notification) {
    return !notification.read_at;
}

async function getNotifications() {
    loading.value = true;

    let path = ROUTES.index;

    if (unread.value) {
        path = ROUTES.unread;
    }

    try {
        const res = await $server.get(path, {
            params: search.value,
        });

        if (res.status == 200) {
            const values = res.data;
            notifications.value = values.data;
            pages.value = values.meta.pagination;
        }
    } catch (error) {
        if (error?.response?.data?.message) {
            $notify.error(error.response.data.message);
        }
    } finally {
        loading.value = false;
    }
}

async function showNotification(notification) {
    try {
        const target = notification?.links?.show;

        if (!target) {
            selectedNotification.value = notification;
            detailsOpen.value = true;
            return;
        }

        const res = await $server.get(target);
        selectedNotification.value = res?.data?.data || notification;
        detailsOpen.value = true;
    } catch (error) {
        selectedNotification.value = notification;
        detailsOpen.value = true;

        if (error?.response?.data?.message) {
            $notify.error(error.response.data.message);
        }
    }
}

async function markAsRead(notification, keepModalOpen = false) {
    if (!notification?.links?.mark_as_read || !isUnread(notification)) return;

    actionLoadingId.value = notification.id;

    try {
        const res = await $server.post(notification.links.mark_as_read);

        if (res.status === 200) {
            notification.read_at = notification.read_at || __("Just now");

            if (selectedNotification.value?.id === notification.id) {
                selectedNotification.value = {
                    ...selectedNotification.value,
                    read_at: notification.read_at,
                };
            }
            $notify.success(__("Notification marked as read"));

            if (!keepModalOpen) {
                detailsOpen.value = false;
            }
        }
    } catch (error) {
        if (error?.response?.data?.message) {
            $notify.error(error.response.data.message);
        }
    } finally {
        actionLoadingId.value = null;
    }
}

async function markAllAsRead() {
    bulkLoading.value = true;
    bulkAction.value = "read";

    try {
        const res = await $server.post(ROUTES.markAllAsRead);

        if (res.status === 200) {
            await getNotifications();
            $notify.success(__("All notifications marked as read"));
        }
    } catch (error) {
        if (error?.response?.data?.message) {
            $notify.error(error.response.data.message);
        }
    } finally {
        bulkLoading.value = false;
        bulkAction.value = "";
    }
}

async function destroyAllNotifications() {
    if (
        !confirm(__("Delete all notifications? This action cannot be undone."))
    ) {
        return;
    }

    bulkLoading.value = true;
    bulkAction.value = "destroy";

    try {
        const res = await $server.delete(ROUTES.destroyAll);

        if (res.status === 200) {
            notifications.value = [];
            detailsOpen.value = false;
            selectedNotification.value = null;
            $notify.success(__("All notifications were deleted"));
        }
    } catch (error) {
        if (error?.response?.data?.message) {
            $notify.error(error.response.data.message);
        }
    } finally {
        bulkLoading.value = false;
        bulkAction.value = "";
    }
}

function openLink(link) {
    if (!link) return;
    window.open(link, "_blank");
}
</script>
