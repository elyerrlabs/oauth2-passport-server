<template>
    <div v-if="items.length" class="mb-6">
        <div
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

        <div class="space-y-2" v-if="toggle">
            <button
                v-for="(item, index) in items"
                :key="index"
                @click="open(item)"
                class="group w-full flex items-center cursor-pointer gap-3 py-1 text-xs rounded-xl transition-all duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600"
                :class="{
                    'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm':
                        isActive(item),
                }"
            >
                <div
                    class="w-8 h-8 flex items-center justify-center rounded-xl bg-gray-200 dark:bg-gray-700 transition-all duration-200 group-hover:scale-110 group-hover:bg-gray-300 dark:group-hover:bg-gray-600"
                >
                    <i
                        class="text-gray-600 dark:text-gray-300 text-xs"
                        :class="item.icon"
                    ></i>
                </div>
                <span class="text-xs">{{ __(item.name) }}</span>
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
const props = defineProps({
    items: {
        type: [Array, Object],
        default: () => [],
    },
    title: {
        type: String,
        default: "My Menu",
    },
    icon: {
        type: String,
        default: "mdi mdi-shield-crown-outline",
    },
    collapse: {
        type: Boolean,
        default: true,
    },
});

const toggle = ref(props.collapse);
const toggleIcon = ref("mdi mdi-arrow-down-circle-outline text-2xl me-2");

const toggleMenu = () => {
    toggle.value = !toggle.value;
    toggleIcon.value = toggle.value
        ? "mdi mdi-arrow-up-circle-outline text-2xl me-2"
        : "mdi mdi-arrow-down-circle-outline text-2xl me-2";
};

const open = (item) => {
    window.location.href = item.route;
};

const isActive = (item) => {
    if (!item?.route) return false;

    const currentPath = window.location.pathname;
    const itemPath = new URL(item.route, window.location.origin).pathname;

    return currentPath === itemPath;
};
</script>
