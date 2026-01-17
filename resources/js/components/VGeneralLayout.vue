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
    <v-layout>
        <template #aside>
            <div
                class="sidebar-container p-2 bg-white dark:bg-gray-800 min-h-full"
            >
                <div class="sidebar-header mb-2">
                    <h2 class="text-lg font-bold text-gray-800 dark:text-white">
                        {{ __("Navigation") }}
                    </h2>
                </div>

                <ul class="sidebar-menu space-y-2">
                    <li v-for="(item, index) in menus" :key="index">
                        <a
                            @click="open(item)"
                            class="group"
                            :class="[
                                'menu-item flex items-center px-2 py-1 cursor-pointer transition-all duration-300 rounded-xl',
                                isActive(item)
                                    ? 'bg-blue-500 text-white shadow-lg   transform scale-105'
                                    : 'text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800   hover:bg-white dark:hover:bg-gray-700 hover:shadow-md',
                            ]"
                        >
                            <div
                                class="menu-icon w-8 h-8 rounded-xl flex items-center justify-center mr-3 transition-all duration-300"
                                :class="
                                    isActive(item)
                                        ? 'bg-white/20 text-white'
                                        : 'bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 group-hover:bg-blue-500 group-hover:text-white'
                                "
                            >
                                <i :class="item.icon" class="mdi text-lg"></i>
                            </div>

                            <span
                                class="menu-text flex-1 font-semibold text-sm"
                            >
                                {{ __(item.name) }}
                            </span>

                            <div v-if="isActive(item)" class="ml-2">
                                <div
                                    class="w-2 h-2 bg-white rounded-full"
                                ></div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </template>
        <template #main>
            <slot />
        </template>
    </v-layout>
</template>

<script setup>
import VLayout from "@/components/VLayout.vue";
import { router, usePage } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";

const menus = ref([]);
const page = usePage();

onMounted(() => {
    menus.value = page.props.menus;
});

const open = (item) => {
    window.location.href = item.route;
};

const isActive = (item) => {
    if (!item?.route) return false;

    // Current query without params
    const currentPath = window.location.pathname;

    //Get only the path without query params
    const itemPath = new URL(item.route, window.location.origin).pathname;

    return currentPath === itemPath;
};
</script>
