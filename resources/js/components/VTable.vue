<!--
OAuth2 Passport Server - Reusable Table Component
Copyright (c) 2026 Elvis Yerel Roman Concha
SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
    <div class="w-full">
        <div v-if="loading" class="py-12 text-center">
            <div
                class="inline-flex items-center gap-2 text-slate-500 dark:text-slate-400"
            >
                <svg
                    class="h-5 w-5 animate-spin"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    ></circle>
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    ></path>
                </svg>
                <span>{{ loadingText || __("Loading...") }}</span>
            </div>
        </div>

        <div v-else-if="!items.length" class="py-12 text-center">
            <i
                :class="[
                    emptyIcon || 'mdi-database',
                    'mb-3 text-4xl text-slate-300 dark:text-slate-600',
                ]"
            ></i>
            <p class="text-slate-500 dark:text-slate-400">
                {{ emptyText || __("No data available") }}
            </p>
        </div>

        <div v-else class="overflow-x-auto">
            <table :class="tableClass">
                <thead v-if="$slots.head" :class="theadClass">
                    <slot name="head"></slot>
                </thead>

                <tbody :class="tbodyClass">
                    <slot
                        :items="paginatedItems"
                        :all-items="items"
                        :current-page="currentPage"
                        :start-index="startIndex"
                    ></slot>
                </tbody>
            </table>

            <div
                v-if="showPagination && totalPages > 1"
                class="mt-4 flex items-center justify-between pt-2"
            >
                <div class="text-sm text-slate-500 dark:text-slate-400">
                    {{ __("Showing") }} {{ startIndex + 1 }} - {{ endIndex }}
                    {{ __("of") }} {{ items.length }}
                </div>

                <div class="flex gap-1">
                    <button
                        :disabled="currentPage === 1"
                        class="rounded border border-slate-200 px-2 py-1 text-sm text-slate-600 transition-colors hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-700 dark:text-slate-400 dark:hover:bg-slate-800"
                        @click="prevPage"
                    >
                        <i class="mdi mdi-chevron-left"></i>
                    </button>

                    <div class="flex gap-1">
                        <button
                            v-for="page in visiblePages"
                            :key="`${page}`"
                            :disabled="page === '...'"
                            :class="[
                                'rounded px-2 py-1 text-sm transition-colors',
                                page === '...'
                                    ? 'cursor-default text-slate-400'
                                    : currentPage === page
                                      ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900'
                                      : 'border border-slate-200 text-slate-600 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-400 dark:hover:bg-slate-800',
                            ]"
                            @click="goToPage(page)"
                        >
                            {{ page }}
                        </button>
                    </div>

                    <button
                        :disabled="currentPage === totalPages"
                        class="rounded border border-slate-200 px-2 py-1 text-sm text-slate-600 transition-colors hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-700 dark:text-slate-400 dark:hover:bg-slate-800"
                        @click="nextPage"
                    >
                        <i class="mdi mdi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref, watch } from "vue";

const props = defineProps({
    items: {
        type: Array,
        default: () => [],
    },
    loading: {
        type: Boolean,
        default: false,
    },
    emptyText: {
        type: String,
        default: "",
    },
    emptyIcon: {
        type: String,
        default: "",
    },
    loadingText: {
        type: String,
        default: "",
    },
    showPagination: {
        type: Boolean,
        default: true,
    },
    perPage: {
        type: Number,
        default: 10,
    },
    tableClass: {
        type: String,
        default: "w-full text-sm",
    },
    theadClass: {
        type: String,
        default: "",
    },
    tbodyClass: {
        type: String,
        default: "",
    },
});

const currentPage = ref(1);

const totalPages = computed(() => Math.max(1, Math.ceil(props.items.length / props.perPage)));

const paginatedItems = computed(() => {
    const start = (currentPage.value - 1) * props.perPage;
    return props.items.slice(start, start + props.perPage);
});

const startIndex = computed(() => (currentPage.value - 1) * props.perPage);
const endIndex = computed(() =>
    Math.min(startIndex.value + props.perPage, props.items.length),
);

const visiblePages = computed(() => {
    const delta = 2;
    const range = [];
    const pages = [];
    let lastPage;

    for (let page = 1; page <= totalPages.value; page++) {
        if (
            page === 1 ||
            page === totalPages.value ||
            (page >= currentPage.value - delta &&
                page <= currentPage.value + delta)
        ) {
            range.push(page);
        }
    }

    range.forEach((page) => {
        if (lastPage) {
            if (page - lastPage === 2) {
                pages.push(lastPage + 1);
            } else if (page - lastPage > 2) {
                pages.push("...");
            }
        }

        pages.push(page);
        lastPage = page;
    });

    return pages;
});

watch(
    () => props.items.length,
    () => {
        if (currentPage.value > totalPages.value) {
            currentPage.value = totalPages.value;
        }
    },
);

function prevPage() {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
}

function nextPage() {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
}

function goToPage(page) {
    if (typeof page === "number") {
        currentPage.value = page;
    }
}
</script>
