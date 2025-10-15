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
    <nav class="flex items-center justify-center mt-8" aria-label="Pagination">
        <div
            class="flex items-center gap-2 bg-white rounded-xl shadow-sm border border-gray-200 p-2"
        >
            <!-- Previous Button -->
            <button
                @click="goToPage(currentPage - 1)"
                :disabled="currentPage === 1"
                class="flex items-center justify-center w-10 h-10 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                :class="
                    currentPage === 1
                        ? 'text-gray-400 cursor-not-allowed'
                        : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600 hover:shadow-sm'
                "
                aria-label="Previous page"
            >
                <i class="mdi mdi-chevron-left text-lg"></i>
            </button>

            <!-- Page Numbers -->
            <div class="flex items-center gap-1">
                <button
                    v-for="page in pagesToShow"
                    :key="page"
                    @click="goToPage(page)"
                    :aria-current="currentPage === page ? 'page' : null"
                    :aria-label="`Go to page ${page}`"
                    class="flex items-center justify-center min-w-10 h-10 rounded-lg text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    :class="[
                        page === '...'
                            ? 'text-gray-400 cursor-default px-2'
                            : currentPage === page
                            ? 'bg-blue-600 text-white shadow-md shadow-blue-200 hover:bg-blue-700 scale-105'
                            : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600 hover:shadow-sm border border-transparent',
                    ]"
                    :disabled="page === '...'"
                >
                    <template v-if="page !== '...'">
                        {{ page }}
                    </template>
                    <template v-else>
                        <i class="mdi mdi-dots-horizontal"></i>
                    </template>
                </button>
            </div>

            <!-- Next Button -->
            <button
                @click="goToPage(currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="flex items-center justify-center w-10 h-10 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                :class="
                    currentPage === totalPages
                        ? 'text-gray-400 cursor-not-allowed'
                        : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600 hover:shadow-sm'
                "
                aria-label="Next page"
            >
                <i class="mdi mdi-chevron-right text-lg"></i>
            </button>
        </div>

        <!-- Page Info (Mobile) -->
        <div class="ml-4 text-sm text-gray-500 hidden sm:block">
            {{ __("Page") }}
            <span class="font-semibold text-gray-700">{{ currentPage }}</span>
            {{ __("of") }}
            <span class="font-semibold text-gray-700">{{ totalPages }}</span>
        </div>
    </nav>
</template>

<script>
export default {
    emits: ["change"],
    props: {
        totalPages: {
            type: Number,
            required: true,
        },
        modelValue: {
            type: Number,
            default: 1,
        },
        maxVisible: {
            type: Number,
            default: 7,
        },
    },
    computed: {
        currentPage: {
            get() {
                return this.modelValue;
            },
            set(val) {
                this.$emit("update:modelValue", val);
            },
        },
        pagesToShow() {
            const total = this.totalPages;
            const max = Math.min(this.maxVisible, 7);
            const current = this.currentPage;

            if (total <= max) {
                return Array.from({ length: total }, (_, i) => i + 1);
            }

            let pages = [1];
            const sidePages = Math.floor((max - 3) / 2);

            let start = Math.max(2, current - sidePages);
            let end = Math.min(total - 1, current + sidePages);

            if (current <= sidePages + 1) {
                end = max - 1;
            }

            if (current >= total - sidePages) {
                start = total - max + 2;
            }

            if (start > 2) pages.push("...");

            for (let i = start; i <= end; i++) {
                pages.push(i);
            }

            if (end < total - 1) pages.push("...");

            pages.push(total);

            return pages;
        },
    },
    methods: {
        goToPage(page) {
            if (page >= 1 && page <= this.totalPages && page !== "...") {
                this.currentPage = page;
                this.$emit("change", page);
            }
        },
    },
};
</script>

<style scoped>
button:not(:disabled) {
    transition: all 0.2s ease-in-out;
}

@keyframes gentle-pulse {
    0%,
    100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

button[aria-current="page"] {
    animation: gentle-pulse 2s ease-in-out infinite;
}

button:focus-visible {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

button:not(:disabled):not(.cursor-default):hover {
    transform: translateY(-1px);
}
</style>
