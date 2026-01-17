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
    <nav
        class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-8 px-4"
        aria-label="Pagination"
    >
        <!-- Page Info -->
        <div
            class="text-sm text-gray-600 dark:text-gray-400 order-2 sm:order-1"
        >
            {{ __("Showing page") }}
            <span class="font-semibold text-gray-900 dark:text-white">{{
                currentPage
            }}</span>
            {{ __("of") }}
            <span class="font-semibold text-gray-900 dark:text-white">{{
                totalPages
            }}</span>
            <span class="hidden md:inline"> - {{ __("Total pages") }}</span>
        </div>

        <!-- Pagination Controls -->
        <div class="flex items-center gap-2 order-1 sm:order-2">
            <!-- Previous Button -->
            <button
                @click="goToPage(currentPage - 1)"
                :disabled="currentPage === 1"
                class="flex items-center justify-center w-10 h-10 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                :class="
                    currentPage === 1
                        ? 'text-gray-400 dark:text-gray-600 cursor-not-allowed'
                        : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 hover:shadow-sm border border-gray-200 dark:border-gray-600'
                "
                aria-label="Previous page"
            >
                <i class="mdi mdi-chevron-left text-lg"></i>
            </button>

            <!-- Page Numbers -->
            <div
                class="flex items-center gap-1 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-1"
            >
                <button
                    v-for="page in pagesToShow"
                    :key="page"
                    @click="goToPage(page)"
                    :aria-current="currentPage === page ? 'page' : null"
                    :aria-label="`Go to page ${page}`"
                    class="flex items-center justify-center min-w-10 h-10 rounded-lg text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                    :class="[
                        page === '...'
                            ? 'text-gray-400 dark:text-gray-500 cursor-default px-2'
                            : currentPage === page
                            ? 'bg-blue-600 text-white shadow-lg shadow-blue-200 dark:shadow-blue-900/50 hover:from-blue-700 hover:to-blue-800 scale-105'
                            : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 border border-transparent',
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
                class="flex items-center justify-center w-10 h-10 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                :class="
                    currentPage === totalPages
                        ? 'text-gray-400 dark:text-gray-600 cursor-not-allowed'
                        : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 hover:shadow-sm border border-gray-200 dark:border-gray-600'
                "
                aria-label="Next page"
            >
                <i class="mdi mdi-chevron-right text-lg"></i>
            </button>
        </div>

        <!-- Quick Navigation (Desktop) -->
        <div class="hidden lg:flex items-center gap-2 order-3">
            <span
                class="text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap"
            >
                {{ __("Go to page") }}:
            </span>
            <div class="relative">
                <input
                    type="number"
                    :min="1"
                    :max="totalPages"
                    v-model.number="quickPage"
                    @keyup.enter="goToQuickPage"
                    class="w-20 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                    :placeholder="__('Page')"
                />
                <button
                    @click="goToQuickPage"
                    class="absolute right-1 top-1/2 transform -translate-y-1/2 p-1 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                >
                    <i class="mdi mdi-arrow-right text-sm"></i>
                </button>
            </div>
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
            default: 5,
        },
    },
    data() {
        return {
            quickPage: null,
        };
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
                this.quickPage = null;
                this.$emit("change", page);
            }
        },
        goToQuickPage() {
            if (
                this.quickPage &&
                this.quickPage >= 1 &&
                this.quickPage <= this.totalPages
            ) {
                this.goToPage(this.quickPage);
            }
        },
    },
};
</script>

<style scoped>
button:not(:disabled) {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes gentle-pulse {
    0%,
    100% {
        transform: scale(1);
        box-shadow: 0 4px 6px -1px rgb(59 130 246 / 0.1),
            0 2px 4px -2px rgb(59 130 246 / 0.1);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 10px 15px -3px rgb(59 130 246 / 0.2),
            0 4px 6px -4px rgb(59 130 246 / 0.1);
    }
}

button[aria-current="page"] {
    animation: gentle-pulse 3s ease-in-out infinite;
}

button:focus-visible {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

button:not(:disabled):not(.cursor-default):hover {
    transform: translateY(-1px);
}

/* Hide number input arrows */
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
}
</style>
