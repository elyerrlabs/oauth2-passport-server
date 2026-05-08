<!--
OAuth2 Passport Server - Reusable Header Component
Copyright (c) 2026 Elvis Yerel Roman Concha
SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
    <header
        class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 mb-4"
    >
        <div class="px-6 py-5 lg:px-8">
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
            >
                <div class="flex items-center gap-3 min-w-0">
                    <div
                        v-if="icon"
                        class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center flex-shrink-0"
                    >
                        <i
                            :class="[
                                icon,
                                'text-slate-600 dark:text-slate-400 text-xl',
                            ]"
                        ></i>
                    </div>

                    <div class="min-w-0">
                        <h1
                            class="text-xl font-semibold text-slate-900 dark:text-white truncate"
                        >
                            {{ title }}
                        </h1>
                        <p
                            v-if="description"
                            class="text-sm text-slate-500 dark:text-slate-400 mt-0.5"
                        >
                            {{ description }}
                        </p>
                    </div>
                </div>

                <!-- Right Section: Actions Slot -->
                <div class="flex items-center gap-2">
                    <slot name="actions"></slot>
                </div>
            </div>

            <!-- Bottom Slot for Tabs or Extra Content -->
            <div v-if="$slots.bottom" class="mt-4 -mb-1">
                <slot name="bottom"></slot>
            </div>
        </div>
    </header>
</template>

<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        default: "",
    },
    icon: {
        type: String,
        default: "",
    },
});
</script>
