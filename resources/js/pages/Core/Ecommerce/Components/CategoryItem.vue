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
    <li>
        <!-- Menu Item -->
        <div
            class="flex items-center px-4 pb-1 hover:bg-gray-100 rounded-md transition-colors cursor-pointer"
        >
            <div
                class="flex items-center flex-1 p-3"
                @click="goTo(item.links?.index)"
            >
                <img
                    class="w-8 h-8 rounded"
                    :src="item.images[0].url"
                    :alt="item.name"
                />
                <span class="text-gray-700 dark:text-gray-200 font-semibold text-sm mx-2">
                    {{ item.name }}
                </span>
                <i
                    :class="[
                        'mdi',
                        item.icon?.icon || 'mdi-folder-outline',
                        'mr-3 text-primary-600 text-lg',
                    ]"
                ></i>
            </div>

            <!-- Toggle arrow -->

            <i
                v-if="hasChildren"
                @click.stop="toggle = !toggle"
                :class="[
                    'mdi font-bold  ',
                    toggle ? 'mdi-chevron-down' : 'mdi-chevron-right',
                    'ml-2 text-gray-500 text-2xl  cursor-pointer transition-transform duration-200',
                ]"
            ></i>
        </div>

        <!-- Recursive Subcategories -->
        <transition name="fade">
            <ul
                v-show="toggle && hasChildren"
                class="ml-6 border-l border-gray-200 pl-3 space-y-1"
            >
                <CategoryItem
                    v-for="child in item.children"
                    :key="child.id"
                    :item="child"
                />
            </ul>
        </transition>
    </li>
</template>

<script>
export default {
    name: "CategoryItem",
    emits: ["blur"],
    props: {
        item: { type: Object, required: true },
    },
    data() {
        return {
            toggle: true,
        };
    },
    computed: {
        hasChildren() {
            return (
                Array.isArray(this.item.children) &&
                this.item.children.length > 0
            );
        },
    },

    methods: {
        setBlur() {
            this.$emit("blur", false);
        },

        goTo(url) {
            if (url) window.location.href = url;
        },
    },
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-4px);
}
</style>
