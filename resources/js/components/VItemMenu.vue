<template>
    <div v-if="items.length" class="mb-6">
        <!-- Menu title section with collapse toggle -->
        <div
            v-if="title"
            class="flex justify-between items-center text-slate-700 dark:text-slate-300 font-medium mb-3 px-1"
        >
            <button
                class="cursor-pointer flex items-center gap-2 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-200 group"
                @click="toggleMenu"
            >
                <span
                    class="text-lg transition-transform duration-300 group-hover:scale-110"
                    :class="icon"
                ></span>
                <span
                    class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400"
                >
                    {{ __(title) }}
                </span>
            </button>
            <button
                class="cursor-pointer p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-all duration-200"
                @click="toggleMenu"
            >
                <span
                    class="text-slate-400 dark:text-slate-500 text-lg transition-transform duration-300"
                    :class="toggleIcon"
                ></span>
            </button>
        </div>

        <!-- Menu items list -->
        <div class="space-y-1.5" v-if="toggle">
            <div v-for="(item, index) in items" :key="index">
                <!-- Main menu item (not clickable if it has submenus) -->
                <div
                    class="group w-full flex items-center justify-between cursor-pointer gap-3 py-2 px-2 text-sm rounded-xl transition-all duration-300 text-slate-700 dark:text-slate-300 hover:bg-dlinear-to-r hover:from-indigo-50/50 hover:to-transparent dark:hover:from-indigo-950/30 dark:hover:to-transparent"
                    :class="{
                        'bg-dlinear-to-r from-indigo-50/80 to-transparent dark:from-indigo-950/40 dark:to-transparent text-indigo-700 dark:text-indigo-300 shadow-sm':
                            isActive(item) || hasActiveSubmenu(item),
                    }"
                >
                    <!-- Left side: icon and name -->
                    <button
                        class="flex-1 flex items-center gap-3 cursor-pointer"
                        @click="handleMenuClick(item)"
                    >
                        <div
                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-dlinear-to-br from-slate-100 to-slate-200/50 dark:from-slate-700/50 dark:to-slate-800/50 transition-all duration-300 group-hover:scale-110 group-hover:shadow-md group-hover:from-indigo-100 group-hover:to-indigo-200/50 dark:group-hover:from-indigo-900/30 dark:group-hover:to-indigo-800/30"
                            :class="{
                                'from-indigo-100 to-indigo-200/50 dark:from-indigo-900/30 dark:to-indigo-800/30 shadow-md':
                                    isActive(item) || hasActiveSubmenu(item),
                            }"
                        >
                            <i
                                class="text-slate-600 dark:text-slate-400 text-sm transition-colors duration-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400"
                                :class="[
                                    item.icon,
                                    {
                                        'text-indigo-600 dark:text-indigo-400':
                                            isActive(item) ||
                                            hasActiveSubmenu(item),
                                    },
                                ]"
                            ></i>
                        </div>
                        <span class="text-sm font-medium">{{
                            __(item.name)
                        }}</span>
                    </button>

                    <!-- Submenu toggle indicator -->
                    <button
                        v-if="item.menus && item.menus.length"
                        @click.stop="toggleSubmenu(item.id)"
                        class="mr-1 p-1.5 shrink-0 rounded-lg cursor-pointer hover:bg-slate-200/50 dark:hover:bg-slate-700/50 transition-all duration-200"
                    >
                        <span
                            class="inline-block transition-transform duration-300 text-slate-400 dark:text-slate-500"
                            :class="{ 'rotate-180': isSubmenuOpen(item.id) }"
                        >
                            <i class="mdi mdi-chevron-down text-sm"></i>
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
                    class="ml-8 mt-0.5 space-y-0.5 border-l-2 border-slate-200/60 dark:border-slate-700/60 pl-4"
                >
                    <button
                        v-for="(submenu, subIndex) in item.menus"
                        :key="`${index}-${subIndex}`"
                        @click="open(submenu)"
                        class="group w-full flex items-center cursor-pointer gap-3 py-1.5 px-2 text-sm rounded-lg transition-all duration-300 text-slate-600 dark:text-slate-400 hover:bg-dlinear-to-r hover:from-indigo-50/30 hover:to-transparent dark:hover:from-indigo-950/20 dark:hover:to-transparent"
                        :class="{
                            'bg-dlinear-to-r from-indigo-50/50 to-transparent dark:from-indigo-950/30 dark:to-transparent text-indigo-700 dark:text-indigo-300 shadow-sm':
                                isActive(submenu),
                        }"
                    >
                        <div
                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-dlinear-to-br from-slate-100 to-slate-200/50 dark:from-slate-700/50 dark:to-slate-800/50 transition-all duration-300 group-hover:scale-110 group-hover:shadow-md group-hover:from-indigo-100 group-hover:to-indigo-200/50 dark:group-hover:from-indigo-900/30 dark:group-hover:to-indigo-800/30"
                            :class="{
                                'from-indigo-100 to-indigo-200/50 dark:from-indigo-900/30 dark:to-indigo-800/30 shadow-md':
                                    isActive(submenu),
                            }"
                        >
                            <i
                                class="text-slate-500 dark:text-slate-400 text-xs transition-colors duration-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400"
                                :class="[
                                    submenu.icon,
                                    {
                                        'text-indigo-600 dark:text-indigo-400':
                                            isActive(submenu),
                                    },
                                ]"
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
const toggleIcon = ref("mdi mdi-chevron-up");

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
        ? "mdi mdi-chevron-up"
        : "mdi mdi-chevron-down";
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

<style scoped>
/* Animaciones suaves */
.group {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Efecto de hover mejorado */
.group:hover {
    transform: translateX(2px);
}

/* Transición suave para submenús */
.border-l-2 {
    transition: border-color 0.3s ease;
}

/* Efecto de brillo en hover */
.group-hover\:shadow-md {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Mejora de accesibilidad visual */
button:focus-visible {
    outline: 2px solid #818cf8;
    outline-offset: 2px;
}

/* Dark mode optimizado */
.dark .group:hover {
    background: linear-dlinear(to right, rgba(99, 102, 241, 0.1), transparent);
}

/* Scrollbar personalizada para el menú */
.menu-scroll {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}

.menu-scroll::-webkit-scrollbar {
    width: 4px;
}

.menu-scroll::-webkit-scrollbar-track {
    background: transparent;
}

.menu-scroll::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 20px;
}

.menu-scroll::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.dark .menu-scroll::-webkit-scrollbar-thumb {
    background: #475569;
}

.dark .menu-scroll::-webkit-scrollbar-thumb:hover {
    background: #64748b;
}
</style>
