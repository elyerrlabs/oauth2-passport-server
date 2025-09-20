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
    <nav class="flex items-center justify-center mt-6">
        <ul class="inline-flex items-center space-x-1">
            <li>
                <button
                    @click="goToPage(currentPage - 1)"
                    :disabled="currentPage === 1"
                    class="px-3 py-1 rounded-lg border text-sm font-medium"
                    :class="
                        currentPage === 1
                            ? 'text-gray-400 border-gray-200 cursor-not-allowed'
                            : 'text-gray-700 border-gray-300 hover:bg-gray-100'
                    "
                >
                    «
                </button>
            </li>

            <li v-for="page in pagesToShow" :key="page">
                <button
                    v-if="page !== '...'"
                    @click="goToPage(page)"
                    :class="[
                        'px-3 py-1 rounded-lg border text-sm font-medium',
                        currentPage === page
                            ? 'bg-primary-600 text-white border-primary-600'
                            : 'text-gray-700 border-gray-300 hover:bg-gray-100',
                    ]"
                >
                    {{ page }}
                </button>
                <span v-else class="px-3 py-1 text-gray-500 select-none">
                    ...
                </span>
            </li>

            <li>
                <button
                    @click="goToPage(currentPage + 1)"
                    :disabled="currentPage === totalPages"
                    class="px-3 py-1 rounded-lg border text-sm font-medium"
                    :class="
                        currentPage === totalPages
                            ? 'text-gray-400 border-gray-200 cursor-not-allowed'
                            : 'text-gray-700 border-gray-300 hover:bg-gray-100'
                    "
                >
                    »
                </button>
            </li>
        </ul>
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
            default: 20,
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
            const max = Math.min(this.maxVisible, 20);
            const current = this.currentPage;
            let pages = [];

            if (total <= max) {
                //
                for (let i = 1; i <= total; i++) {
                    pages.push(i);
                }
            } else {
                pages.push(1);

                let start = Math.max(2, current - Math.floor((max - 4) / 2));
                let end = Math.min(
                    total - 1,
                    current + Math.floor((max - 4) / 2)
                );

                if (start > 2) pages.push("...");
                for (let i = start; i <= end; i++) {
                    pages.push(i);
                }
                if (end < total - 1) pages.push("...");

                pages.push(total);
            }
            return pages;
        },
    },
    methods: {
        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;

                this.$emit("change", page);
            }
        },
    },
};
</script>
