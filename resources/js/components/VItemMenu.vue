<template>
    <div v-if="items.length" class="mb-6">
        <!-- Menu title section with collapse toggle -->
        <div
            v-if="title"
            class="flex justify-between text-gray-700 dark:text-gray-300 font-semibold mb-2"
        >
            <button class="cursor-pointer" @click="toggleMenu">
                <span :class="icon" class="text-xl me-2"></span>
                <span>
                    {{ __(title) }}
                </span>
            </button>
            <button class="cursor-pointer" @click="toggleMenu">
                <span :class="toggleIcon"></span>
            </button>
        </div>

        <!-- Menu items list -->
        <div class="space-y-2" v-if="toggle">
            <div v-for="(item, index) in items" :key="index">
                <!-- Main menu item (not clickable if it has submenus) -->
                <div
                    class="group w-full flex items-center justify-between cursor-pointer gap-3 py-1 text-xs rounded-xl transition-all duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600"
                    :class="{
                        'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm':
                            isActive(item) || hasActiveSubmenu(item),
                    }"
                >
                    <!-- Left side: icon and name -->
                    <button
                        class="flex-1 flex items-center gap-2 cursor-pointer"
                        @click="handleMenuClick(item)"
                    >
                        <div
                            class="w-8 h-8 flex items-center justify-center rounded-xl bg-gray-200 dark:bg-gray-700 transition-all duration-200 group-hover:scale-110 group-hover:bg-gray-300 dark:group-hover:bg-gray-600"
                        >
                            <i
                                class="text-gray-600 dark:text-gray-300 text-xs"
                                :class="item.icon"
                            ></i>
                        </div>
                        <span class="text-md">{{ __(item.name) }}</span>
                    </button>

                    <!-- Submenu toggle indicator -->
                    <button
                        v-if="item.menus && item.menus.length"
                        @click.stop="toggleSubmenu(item.id)"
                        class="mr-2 p-1 shrink-0 rounded-md cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200"
                    >
                        <span
                            class="inline-block transition-transform duration-200"
                            :class="{ 'rotate-180': isSubmenuOpen(item.id) }"
                        >
                            <i
                                class="mdi mdi-chevron-down text-sm text-gray-400"
                            ></i>
                        </span>
                    </button>
                </div>

                <!-- Submenus (shown by default, can be toggled) -->
                <div
                    v-if="
                        item.menus &&
                        item.menus.length &&
                        isSubmenuOpen(item.id)
                    "
                    class="ml-2 mt-1 space-y-1 border-l-2 border-gray-200 dark:border-gray-600 pl-4"
                >
                    <button
                        v-for="(submenu, subIndex) in item.menus"
                        :key="`${index}-${subIndex}`"
                        @click="open(submenu)"
                        class="group w-full flex items-center cursor-pointer gap-2 py-1.5 text-xs rounded-lg transition-all duration-200 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600"
                        :class="{
                            'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm':
                                isActive(submenu),
                        }"
                    >
                        <div
                            class="w-7 h-7 flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700 transition-all duration-200 group-hover:scale-110 group-hover:bg-gray-200 dark:group-hover:bg-gray-600"
                        >
                            <i
                                class="text-gray-500 dark:text-gray-400 text-xs"
                                :class="submenu.icon"
                            ></i>
                        </div>
                        <span class="text-sm">{{ __(submenu.name) }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";

const props = defineProps({
    // Menu items array with optional submenus
    items: {
        type: [Array, Object],
        default: () => [],
    },
    // Section title (e.g., "Administration")
    title: {
        type: String,
        default: null,
    },
    // Icon class for the title section
    icon: {
        type: String,
        default: "mdi mdi-shield-crown-outline",
    },
    // Whether the menu section starts expanded or collapsed
    collapse: {
        type: Boolean,
        default: true,
    },
});

// Menu section toggle state
const toggle = ref(props.collapse);
const toggleIcon = ref("mdi mdi-arrow-down-circle-outline text-2xl me-2");

// Track which submenus are currently open (all open by default)
const openSubmenus = ref({});

/**
 * Initialize all submenus as open by default
 */
props.items.forEach((item) => {
    if (item.menus && item.menus.length) {
        openSubmenus.value[item.id] = true;
    }
});

/**
 * Toggle the entire menu section (collapse/expand)
 */
const toggleMenu = () => {
    toggle.value = !toggle.value;
    toggleIcon.value = toggle.value
        ? "mdi mdi-arrow-up-circle-outline text-2xl me-2"
        : "mdi mdi-arrow-down-circle-outline text-2xl me-2";
};

/**
 * Handle click on a main menu item
 * If it has submenus, navigate to main route
 * If no submenus, navigate directly
 */
const handleMenuClick = (item) => {
    if (item?.route) {
        open(item);
    }
};

/**
 * Toggle a specific submenu open/closed
 */
const toggleSubmenu = (menuId) => {
    openSubmenus.value = {
        ...openSubmenus.value,
        [menuId]: !openSubmenus.value[menuId],
    };
};

/**
 * Check if a specific submenu is currently open
 */
const isSubmenuOpen = (menuId) => {
    return openSubmenus.value[menuId] ?? true; // Default to open
};

/**
 * Navigate to the item's route
 */
const open = (item) => {
    if (item?.route) {
        window.location.href = item.route;
    }
};

/**
 * Check if the current route matches the item's route
 */
const isActive = (item) => {
    if (!item?.route) return false;

    const currentPath = window.location.pathname;
    const itemPath = new URL(item.route, window.location.origin).pathname;

    return currentPath === itemPath;
};

/**
 * Check if any submenu under this parent is currently active
 * Used to highlight the parent when a child is active
 */
const hasActiveSubmenu = (item) => {
    if (!item.menus || !item.menus.length) return false;
    return item.menus.some((submenu) => isActive(submenu));
};
</script>
