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
    <v-admin-layout>
        <div
            class="min-h-screen bg-gray-50 dark:bg-gray-900 py-4 px-3 sm:px-6 lg:px-8"
        >
            <div class="max-w-6xl mx-auto">
                <!-- Loading State -->
                <div
                    v-if="loading"
                    class="flex justify-center items-center py-12"
                >
                    <div class="text-center">
                        <div
                            class="w-12 h-12 sm:w-16 sm:h-16 bg-blue-100 dark:bg-blue-900 rounded-xl flex items-center justify-center mx-auto mb-3"
                        >
                            <i
                                class="fas fa-spinner fa-spin text-blue-600 dark:text-blue-500 text-lg"
                            ></i>
                        </div>
                        <h3
                            class="text-base font-semibold text-gray-900 dark:text-white mb-2"
                        >
                            {{ __("Loading customer data") }}
                        </h3>
                    </div>
                </div>

                <!-- Customer Details -->
                <div v-else class="space-y-4 sm:space-y-6">
                    <!-- Header -->
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
                    >
                        <h1
                            class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white"
                        >
                            {{ __("Customers") }}
                        </h1>

                        <div
                            class="flex items-center bg-white dark:bg-gray-800 rounded-lg px-3 py-2 border border-gray-200 dark:border-gray-700"
                        >
                            <i
                                class="fas fa-list-ol text-gray-400 dark:text-gray-500 text-sm mr-2"
                            ></i>
                            <select
                                v-model="search.per_page"
                                @change="getCustomer"
                                class="border-0 focus:ring-0 text-gray-700 dark:text-gray-300 bg-transparent dark:bg-gray-800 text-sm"
                            >
                                <option value="10">
                                    10 {{ __("per page") }}
                                </option>
                                <option value="15">
                                    15 {{ __("per page") }}
                                </option>
                                <option value="25">
                                    25 {{ __("per page") }}
                                </option>
                                <option value="50">
                                    50 {{ __("per page") }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Customers List -->
                    <div class="space-y-4">
                        <div
                            v-for="(item, index) in customers"
                            :key="index"
                            class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden"
                        >
                            <!-- Customer Header -->
                            <div
                                class="bg-blue-600 dark:bg-blue-700 px-4 sm:px-6 py-5 sm:py-8"
                            >
                                <div
                                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between"
                                >
                                    <div
                                        class="flex items-center space-x-4 mb-4 sm:mb-0"
                                    >
                                        <div
                                            class="w-12 h-12 sm:w-16 sm:h-16 bg-white/20 rounded-lg sm:rounded-xl flex items-center justify-center border border-white/30"
                                        >
                                            <span
                                                class="text-white text-lg sm:text-xl font-bold"
                                            >
                                                {{ customerInitials(item) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h1
                                                class="text-lg sm:text-xl font-bold text-white truncate"
                                            >
                                                {{ item.name }}
                                                {{ item.last_name }}
                                            </h1>
                                            <p
                                                class="text-blue-100 mt-1 text-sm truncate"
                                            >
                                                {{ item.email }}
                                            </p>
                                        </div>
                                    </div>
                                    <div
                                        class="bg-white/20 rounded-lg px-3 py-2 border border-white/30"
                                    >
                                        <span
                                            class="text-white font-semibold text-sm"
                                        >
                                            {{ item.checkouts_count }}
                                            {{ __("Orders") }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Customer Information -->
                            <div class="p-4 sm:p-6">
                                <div
                                    class="grid grid-cols-1 lg:grid-cols-2 gap-6"
                                >
                                    <!-- Customer Information -->
                                    <div class="space-y-4">
                                        <h2
                                            class="text-base font-bold text-gray-900 dark:text-white flex items-center"
                                        >
                                            <i
                                                class="fas fa-user-circle mr-2 text-blue-500 dark:text-blue-400"
                                            ></i>
                                            {{ __("Customer Information") }}
                                        </h2>

                                        <div class="space-y-3">
                                            <div
                                                class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700"
                                            >
                                                <span
                                                    class="text-sm font-medium text-gray-600 dark:text-gray-400"
                                                    >{{ __("ID") }}:</span
                                                >
                                                <span
                                                    class="text-sm text-gray-900 dark:text-white font-mono truncate ml-2 max-w-[120px]"
                                                >
                                                    {{ item.id }}
                                                </span>
                                            </div>

                                            <div
                                                class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700"
                                            >
                                                <span
                                                    class="text-sm font-medium text-gray-600 dark:text-gray-400"
                                                    >{{ __("Name") }}:</span
                                                >
                                                <span
                                                    class="text-sm text-gray-900 dark:text-white truncate ml-2"
                                                >
                                                    {{ item.name }}
                                                    {{ item.last_name }}
                                                </span>
                                            </div>

                                            <div
                                                class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700"
                                            >
                                                <span
                                                    class="text-sm font-medium text-gray-600 dark:text-gray-400"
                                                    >{{ __("Email") }}:</span
                                                >
                                                <a
                                                    :href="`mailto:${item.email}`"
                                                    class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 truncate ml-2 max-w-[180px]"
                                                >
                                                    {{ item.email }}
                                                </a>
                                            </div>

                                            <div
                                                class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700"
                                            >
                                                <span
                                                    class="text-sm font-medium text-gray-600 dark:text-gray-400"
                                                    >{{ __("Phone") }}:</span
                                                >
                                                <div
                                                    class="flex items-center space-x-2"
                                                >
                                                    <span
                                                        v-if="item.full_phone"
                                                        class="text-sm text-gray-900 dark:text-white"
                                                    >
                                                        {{ item.full_phone }}
                                                    </span>
                                                    <span
                                                        v-else
                                                        class="text-sm text-gray-500 dark:text-gray-400"
                                                    >
                                                        {{ __("Not provided") }}
                                                    </span>
                                                    <a
                                                        v-if="item.full_phone"
                                                        :href="`https://wa.me/${item.full_phone.replace(
                                                            /\D/g,
                                                            ''
                                                        )}`"
                                                        target="_blank"
                                                        class="w-7 h-7 bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white rounded-full flex items-center justify-center transition-colors flex-shrink-0"
                                                    >
                                                        <i
                                                            class="fab fa-whatsapp text-xs"
                                                        ></i>
                                                    </a>
                                                </div>
                                            </div>

                                            <div
                                                class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700"
                                            >
                                                <span
                                                    class="text-sm font-medium text-gray-600 dark:text-gray-400"
                                                    >{{ __("City") }}:</span
                                                >
                                                <span
                                                    class="text-sm text-gray-900 dark:text-white"
                                                >
                                                    {{
                                                        item.city ||
                                                        __("Not provided")
                                                    }}
                                                </span>
                                            </div>

                                            <div
                                                class="flex justify-between items-start py-2"
                                            >
                                                <span
                                                    class="text-sm font-medium text-gray-600 dark:text-gray-400"
                                                    >{{ __("Address") }}:</span
                                                >
                                                <span
                                                    class="text-sm text-gray-900 dark:text-white text-right max-w-[200px] truncate"
                                                >
                                                    {{
                                                        item.address ||
                                                        __("Not provided")
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Order Statistics -->
                                    <div class="space-y-4">
                                        <h2
                                            class="text-base font-bold text-gray-900 dark:text-white flex items-center"
                                        >
                                            <i
                                                class="fas fa-chart-bar mr-2 text-green-500 dark:text-green-400"
                                            ></i>
                                            {{ __("Order Statistics") }}
                                        </h2>

                                        <div class="space-y-3">
                                            <div
                                                class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700"
                                            >
                                                <span
                                                    class="text-sm font-medium text-gray-600 dark:text-gray-400"
                                                    >{{
                                                        __("Total Orders")
                                                    }}:</span
                                                >
                                                <span
                                                    class="text-lg font-bold text-gray-900 dark:text-white"
                                                >
                                                    {{ item.checkouts_count }}
                                                </span>
                                            </div>

                                            <div class="space-y-2">
                                                <span
                                                    class="text-sm font-medium text-gray-600 dark:text-gray-400 block"
                                                >
                                                    {{
                                                        __(
                                                            "Totals by Currency"
                                                        )
                                                    }}:
                                                </span>
                                                <div
                                                    v-for="(
                                                        c, idx
                                                    ) in item.checkout"
                                                    :key="idx"
                                                    class="flex justify-between items-center bg-gray-50 dark:bg-gray-700 rounded px-3 py-2"
                                                >
                                                    <span
                                                        class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                                    >
                                                        {{ c.currency }}
                                                    </span>
                                                    <span
                                                        class="text-sm font-bold text-green-600 dark:text-green-400"
                                                    >
                                                        {{ c.total }}
                                                    </span>
                                                </div>
                                                <div
                                                    v-if="
                                                        !item.checkout ||
                                                        item.checkout.length ===
                                                            0
                                                    "
                                                    class="text-gray-500 dark:text-gray-400 text-center py-3 text-sm"
                                                >
                                                    {{ __("No currency data") }}
                                                </div>
                                            </div>

                                            <!-- Progress Bar -->
                                            <div class="mt-4">
                                                <div
                                                    class="flex justify-between items-center mb-1"
                                                >
                                                    <span
                                                        class="text-sm font-medium text-gray-600 dark:text-gray-400"
                                                    >
                                                        {{
                                                            __("Order Progress")
                                                        }}
                                                    </span>
                                                    <span
                                                        class="text-xs text-gray-500 dark:text-gray-400"
                                                    >
                                                        {{
                                                            item.checkouts_count
                                                        }}
                                                        {{ __("Orders") }}
                                                    </span>
                                                </div>
                                                <div
                                                    class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2"
                                                >
                                                    <div
                                                        class="bg-blue-500 dark:bg-blue-600 h-2 rounded-full transition-all duration-300"
                                                        :style="{
                                                            width:
                                                                Math.min(
                                                                    (item.checkouts_count /
                                                                        10) *
                                                                        100,
                                                                    100
                                                                ) + '%',
                                                        }"
                                                    ></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div
                                class="px-4 sm:px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600"
                            >
                                <div class="flex flex-wrap gap-2 justify-end">
                                    <a
                                        :href="`mailto:${item.email}`"
                                        class="px-3 py-2 bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white rounded-lg flex items-center space-x-2 text-sm transition-colors"
                                    >
                                        <i class="fas fa-envelope text-xs"></i>
                                        <span>{{ __("Send Email") }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="flex justify-center mt-6">
                    <v-paginate
                        :total-pages="pages.total_pages"
                        v-model="search.page"
                        @change="getCustomer"
                    />
                </div>
            </div>
        </div>
    </v-admin-layout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import VAdminLayout from "@Ecommerce/components/VAdminLayout.vue";
import VPaginate from "@/components/VPaginate.vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const loading = ref(true);
const customers = ref([]);
const pages = ref({ total_pages: 0 });
const search = ref({ page: 1, per_page: 15 });

onMounted(() => {
    getCustomer();
});

const customerInitials = (item) => {
    if (item.name && item.last_name) {
        return item.name.charAt(0) + item.last_name.charAt(0);
    }
    return "CU";
};

const getCustomer = async () => {
    try {
        const res = await $server.get(page.props.api.customers, {
            params: search.value,
        });

        if (res.status == 200) {
            customers.value = res.data.data;
            pages.value = res.data.meta?.pagination || { total_pages: 0 };
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    } finally {
        loading.value = false;
    }
};
</script>

<style>
@media (max-width: 475px) {
    .text-truncate-mobile {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
}

* {
    -webkit-tap-highlight-color: transparent;
}

@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

img {
    max-width: 100%;
    height: auto;
    display: block;
}
</style>
