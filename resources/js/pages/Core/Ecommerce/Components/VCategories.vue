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
    <div class="py-2 max-h-screen overflow-y-auto">
        <!-- Loader -->
        <div v-if="loading" class="flex justify-center items-center py-6">
            <svg
                class="animate-spin h-6 w-6 text-primary-600"
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
                />
                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                />
            </svg>
        </div>

        <!-- Recursive categories -->
        <ul v-else class="space-y-1">
            <CategoryItem
                v-for="category in categories"
                :key="category.id"
                :item="category"
            />
        </ul>
    </div>
</template>

<script>
import CategoryItem from "./CategoryItem.vue";

export default {
    name: "CategoryList",
    components: { CategoryItem },

    data() {
        return {
            categories: [],
            loading: true,
        };
    },

    created() {
        this.getCategories();
    },

    methods: {
        async getCategories() {
            this.loading = true;
            try {
                const res = await this.$server.get(
                    this.$page.props.api.ecommerce.categories,
                    {
                        params: {
                            parent: "",
                        },
                    }
                );
                if (res.status === 200) {
                    this.categories = res.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
