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
    <v-layout>
        <template #aside>
            <div>
                <h3
                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 mt-6"
                >
                    {{ __("Navigation") }}
                </h3>
                <button
                    v-for="(item, index) in menus"
                    :key="index"
                    @click="open(item)"
                    class="w-full flex items-center space-x-3 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100 transition-colors duration-200"
                    :class="{
                        'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white':
                            isActive(item),
                        'bg-transparent': !isActive(item),
                    }"
                >
                    <div
                        class="w-8 h-8 bg-green-600 dark:bg-green-500 rounded-lg flex items-center justify-center"
                    >
                        <i
                            :class="['mdi', item.icon, 'text-white text-sm']"
                        ></i>
                    </div>
                    <span>{{ __(item.name) }}</span>
                </button>
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
import { ref, onMounted, onBeforeUnmount } from "vue";

const page = usePage();
const isSidebarOpen = ref(false);
const app_name = ref("");
const menus = ref([]);

onMounted(() => {
    app_name.value = page.props.org_name ?? "";
    menus.value = page.props.partner_routes ?? [];
    setupEventListeners();
});

const open = (item) => {
    router.visit(item.route);
    if (window.innerWidth < 1024) {
        isSidebarOpen.value = false;
    }
};

const isActive = (item) => {
    return item.route == `${window.location.origin}${window.location.pathname}`;
};

const handleResize = () => {
    if (window.innerWidth >= 1024) {
        isSidebarOpen.value = false;
    }
};

const setupEventListeners = () => {
    window.addEventListener("resize", handleResize);
};

onBeforeUnmount(() => {
    window.removeEventListener("resize", handleResize);
});
</script>

<style scoped>
/* Transiciones suaves para cambios de tema */
button {
    transition: all 0.2s ease-in-out;
}

/* Mejora de contraste para estados activos */
.bg-gray-300.dark\:bg-gray-600 {
    border-left: 3px solid rgb(34 197 94); /* green-500 */
}

/* Estados hover mejorados */
.hover\:bg-gray-100:hover {
    transform: translateX(2px);
}

.dark .hover\:bg-gray-700:hover {
    transform: translateX(2px);
}

.text-white {
    filter: brightness(0.95);
}

.dark .text-white {
    filter: brightness(1.1);
}
</style>
