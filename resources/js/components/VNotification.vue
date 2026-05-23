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
    <button
        v-if="page.props.user?.id"
        @click="open"
        class="relative cursor-pointer w-8 h-8 text-green-600 dark:text-green-400 hover:text-green-400 dark:hover:text-white bg-white dark:bg-gray-800 rounded-full hover:bg-white dark:hover:bg-gray-700 transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
    >
        <i class="mdi mdi-bell text-lg"></i>

        <!-- Badge: solo número total sin leer -->
        <span
            v-if="unreadNotifications"
            class="absolute -top-1 -right-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold leading-none text-white bg-red-500 rounded-full"
        >
            {{ unreadNotifications }}
        </span>
    </button>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { router, usePage } from "@inertiajs/vue3";

const page = usePage();
const unreadNotifications = ref(0);

onMounted(async () => {
    await getUnreadNotifications();
});

const getUnreadNotifications = async () => {
    try {
        const res = await $server.get(
            "/system/api/user/user/notifications/unread",
        );
        if (res.status === 200) {
            unreadNotifications.value = res.data.meta.pagination.total;
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify?.error(e.response.data.message);
        }
    }
};

const open = () => {
    window.location.href = page.props.auth_routes.notifications;
};
</script>
