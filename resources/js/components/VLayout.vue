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
    <div class="h-screen flex flex-col">
        <header
            class="flex justify-between items-center gap-4 p-2 border-b border-b-gray-200 dark:border-b-gray-700"
        >
            <div class="flex items-center gap-4">
                <button
                    @click="toggleSidebar"
                    class="p-2 rounded cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600"
                    aria-label="Toggle sidebar"
                >
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                    </svg>
                </button>
                <a
                    :href="page.props.auth_routes.dashboard"
                    class="text-gray-900 dark:text-white font-medium"
                >
                    {{ page.props.app_name }}
                </a>
            </div>

            <div class="flex items-center gap-4">
                <v-theme />
                <v-notification />
                <v-profile />
            </div>
        </header>

        <!-- Cuerpo principal -->
        <section class="flex-1 flex overflow-hidden">
            <nav
                v-show="sidebarVisible"
                class="py-4 px-2 space-y-2 overflow-y-auto border-r border-r-gray-200 dark:border-r-gray-700 w-64"
            >
                <slot name="aside" />
            </nav>

            <main class="flex-1 overflow-y-auto p-4">
                <slot name="main"></slot>
            </main>
        </section>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import VNotification from "@/components/VNotification.vue";
import VProfile from "@/components/VProfile.vue";
import VTheme from "@/components/VTheme.vue";
import { usePage } from "@inertiajs/vue3";
import VButton from "@/components/VButton.vue";

const sidebarVisible = ref(true);

onMounted(() => {
    sidebarVisible.value =
        localStorage.getItem("sidebar") === "true" ? true : false;
});

const toggleSidebar = () => {
    sidebarVisible.value = !sidebarVisible.value;
    localStorage.setItem("sidebar", sidebarVisible.value);
};

const page = usePage();
</script>
