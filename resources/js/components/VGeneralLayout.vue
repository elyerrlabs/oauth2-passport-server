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
            <nav
                class="sidebar-container p-2 bg-white dark:bg-gray-800 min-h-full"
                aria-label="Main navigation"
            >
                <ul class="sidebar-menu space-y-2">
                    <li
                        v-for="(item, index) in page.props.menus"
                        :key="index"
                        class="menu-item-wrapper"
                    >
                        <!-- Parent item: if it has children it's a toggle button, otherwise a direct link -->
                        <component
                            :is="item.children ? 'button' : 'a'"
                            :href="item.children ? undefined : item.route"
                            @click="handleMenuClick(item)"
                            class="group"
                            :class="[
                                'menu-item flex items-center px-2 py-1 cursor-pointer transition-all duration-300 rounded-xl w-full text-left',
                                isActive(item)
                                    ? 'bg-blue-500 text-white shadow-lg transform scale-105'
                                    : 'text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-white dark:hover:bg-gray-700 hover:shadow-md',
                            ]"
                            :aria-expanded="
                                item.children ? isExpanded(item) : undefined
                            "
                            :aria-current="isActive(item) ? 'page' : undefined"
                        >
                            <div
                                class="menu-icon w-8 h-8 rounded-xl flex items-center justify-center mr-3 transition-all duration-300 flex-shrink-0"
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

                            <!-- Active indicator dot -->
                            <div v-if="isActive(item)" class="ml-2">
                                <div
                                    class="w-2 h-2 bg-white rounded-full"
                                ></div>
                            </div>

                            <!-- Expand/collapse chevron for submenus -->
                            <i
                                v-if="item.children"
                                class="mdi ml-2 transition-transform duration-200"
                                :class="{
                                    'mdi-chevron-down': !isExpanded(item),
                                    'mdi-chevron-up': isExpanded(item),
                                }"
                            ></i>
                        </component>

                        <!-- Submenu list -->
                        <ul
                            v-if="item.children && isExpanded(item)"
                            class="submenu mt-1 ml-4 space-y-1 border-l-2 border-blue-200 dark:border-blue-800 pl-2"
                        >
                            <li
                                v-for="(child, childIndex) in item.children"
                                :key="childIndex"
                            >
                                <a
                                    :href="child.route"
                                    @click.prevent="open(child)"
                                    class="flex items-center px-3 py-1.5 rounded-lg text-sm transition-colors duration-200"
                                    :class="
                                        isActive(child)
                                            ? 'bg-blue-500 text-white shadow-sm'
                                            : 'text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400'
                                    "
                                    :aria-current="
                                        isActive(child) ? 'page' : undefined
                                    "
                                >
                                    <i
                                        v-if="child.icon"
                                        :class="child.icon"
                                        class="mdi mr-2 text-base"
                                    ></i>
                                    {{ __(child.name) }}
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </template>
        <template #main>
            <slot />
        </template>
    </v-layout>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import VLayout from "@/components/VLayout.vue";
import { router, usePage } from "@inertiajs/vue3";

const page = usePage();

/**
 * Tracks manually toggled parent items.
 * Key: index of the menu item (stringified to avoid reactivity issues)
 * Value: true if expanded manually, false if collapsed manually
 */
const manualExpand = ref({});

/**
 * Expands a parent item if it or any of its children is active.
 * If user has manually toggled it off, it will still open when a child becomes active.
 */
const isExpanded = (item) => {
    if (!item.children) return false;

    // Auto‑expand if any child is active (even if manually closed)
    if (hasActiveChild(item)) return true;

    // Fall back to manual state
    const key = getItemKey(item);
    return manualExpand.value[key] ?? false;
};

/**
 * Simple key based on the item's name and route (or children length)
 */
const getItemKey = (item) => {
    return `${item.name}-${item.route ?? ""}-${item.children?.length ?? 0}`;
};

/**
 * Recursively check if any descendant is active.
 * Works for unlimited nesting depth, though here we only render one level.
 */
const hasActiveChild = (item) => {
    if (!item.children) return false;
    return item.children.some(
        (child) => isActive(child) || hasActiveChild(child),
    );
};

/**
 * Determines if the current route matches the menu item.
 * Works for both parent and child items.
 */
const isActive = (item) => {
    if (!item?.route) return false;
    const currentPath = window.location.pathname;
    const itemPath = new URL(item.route, window.location.origin).pathname;
    return currentPath === itemPath;
};

/**
 * Handles click on a menu item:
 * - If it has children, toggle the manual expansion state.
 * - Otherwise, navigate to the route.
 */
const handleMenuClick = (item) => {
    if (item.children) {
        const key = getItemKey(item);
        manualExpand.value = {
            ...manualExpand.value,
            [key]: !isExpanded(item),
        };
    } else {
        open(item);
    }
};

/**
 * Navigate to a given item’s route.
 */
const open = (item) => {
    if (item?.route) {
        router.visit(item.route);
    }
};

/**
 * When the route changes (e.g., navigation to a child),
 * we don't need to reset manual state — the auto‑expand logic
 * will override manual state for the active branch.
 * But to keep things tidy, we could reset all manual toggles
 * when a child becomes active. Leaving it simple.
 */
</script>
