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
            class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50/30 py-6 px-4 sm:px-6 lg:px-8"
        >
            <div class="max-w-6xl mx-auto">
                <!-- Loading State -->
                <div
                    v-if="loading"
                    class="flex justify-center items-center py-20"
                >
                    <div class="text-center">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg"
                        >
                            <i
                                class="fas fa-spinner fa-spin text-blue-600 text-2xl"
                            ></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            {{ __("Loading customer data...") }}
                        </h3>
                    </div>
                </div>

                <!-- Customer Details -->
                <div v-else class="space-y-6">
                    <div class="w-full flex justify-between">
                        <h1 class="font-medium md:text-2xl text-gray-500">
                            {{ __("List of customer") }}
                        </h1>

                        <div
                            class="flex items-center space-x-2 bg-white rounded-xl px-3 py-2 border border-gray-200 shadow-sm"
                        >
                            <i class="fas fa-list-ol text-gray-400"></i>
                            <select
                                v-model="search.per_page"
                                @change="getCustomer"
                                class="border-0 focus:ring-0 text-gray-700 font-medium bg-transparent"
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
                                <option value="100">
                                    100 {{ __("per page") }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div
                        v-for="(item, index) in customers"
                        :key="index"
                        class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden"
                    >
                        <!-- Customer Header -->
                        <div
                            class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-8"
                        >
                            <div
                                class="flex flex-col md:flex-row md:items-center md:justify-between"
                            >
                                <div
                                    class="flex items-center space-x-6 mb-4 md:mb-0"
                                >
                                    <div
                                        class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border-2 border-white/30"
                                    >
                                        <span
                                            class="text-white text-2xl font-bold"
                                        >
                                            {{ customerInitials(item) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h1
                                            class="text-2xl md:text-3xl font-bold text-white"
                                        >
                                            {{ item.name }} {{ item.last_name }}
                                        </h1>
                                        <p class="text-blue-100 mt-1 text-lg">
                                            {{ item.email }}
                                        </p>
                                    </div>
                                </div>
                                <div
                                    class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 border border-white/30"
                                >
                                    <span class="text-white font-semibold">
                                        {{ item.checkouts_count }}
                                        {{ __("Orders") }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Customer Information -->
                                <div class="space-y-6">
                                    <h2
                                        class="text-xl font-bold text-gray-900 flex items-center"
                                    >
                                        <i
                                            class="fas fa-user-circle mr-3 text-blue-500"
                                        ></i>
                                        {{ __("Customer Information") }}
                                    </h2>

                                    <div class="space-y-4">
                                        <div
                                            class="flex justify-between items-center pb-3 border-b border-gray-100"
                                        >
                                            <span
                                                class="font-medium text-gray-700"
                                                >{{ __("Customer ID") }}:</span
                                            >
                                            <span
                                                class="text-gray-900 font-mono"
                                                >{{ item.id }}</span
                                            >
                                        </div>

                                        <div
                                            class="flex justify-between items-center pb-3 border-b border-gray-100"
                                        >
                                            <span
                                                class="font-medium text-gray-700"
                                                >{{ __("Full Name") }}:</span
                                            >
                                            <span class="text-gray-900"
                                                >{{ item.name }}
                                                {{ item.last_name }}</span
                                            >
                                        </div>

                                        <div
                                            class="flex justify-between items-center pb-3 border-b border-gray-100"
                                        >
                                            <span
                                                class="font-medium text-gray-700"
                                                >{{ __("Email") }}:</span
                                            >
                                            <a
                                                :href="`mailto:${item.email}`"
                                                class="text-blue-600 hover:text-blue-700 font-medium"
                                            >
                                                {{ item.email }}
                                            </a>
                                        </div>

                                        <div
                                            class="flex justify-between items-center pb-3 border-b border-gray-100"
                                        >
                                            <span
                                                class="font-medium text-gray-700"
                                                >{{ __("Phone") }}:</span
                                            >
                                            <div
                                                class="flex items-center space-x-3"
                                            >
                                                <span
                                                    v-if="item.full_phone"
                                                    class="text-gray-900"
                                                    >{{ item.full_phone }}</span
                                                >
                                                <span
                                                    v-else
                                                    class="text-gray-500"
                                                    >{{
                                                        __("Not provided")
                                                    }}</span
                                                >

                                                <a
                                                    v-if="item.full_phone"
                                                    :href="`https://wa.me/${item.full_phone.replace(
                                                        /\D/g,
                                                        ''
                                                    )}`"
                                                    target="_blank"
                                                    class="w-8 h-8 bg-green-500 hover:bg-green-600 text-white rounded-full flex items-center justify-center transition-colors"
                                                >
                                                    <i
                                                        class="fab fa-whatsapp text-sm"
                                                    ></i>
                                                </a>
                                            </div>
                                        </div>

                                        <div
                                            class="flex justify-between items-center pb-3 border-b border-gray-100"
                                        >
                                            <span
                                                class="font-medium text-gray-700"
                                                >{{ __("City") }}:</span
                                            >
                                            <span class="text-gray-900">{{
                                                item.city || __("Not provided")
                                            }}</span>
                                        </div>

                                        <div
                                            class="flex justify-between items-center"
                                        >
                                            <span
                                                class="font-medium text-gray-700"
                                                >{{ __("Address") }}:</span
                                            >
                                            <span
                                                class="text-gray-900 text-right max-w-xs"
                                                >{{
                                                    item.address ||
                                                    __("Not provided")
                                                }}</span
                                            >
                                        </div>
                                    </div>
                                </div>

                                <!-- Order Statistics -->
                                <div class="space-y-6">
                                    <h2
                                        class="text-xl font-bold text-gray-900 flex items-center"
                                    >
                                        <i
                                            class="fas fa-chart-bar mr-3 text-green-500"
                                        ></i>
                                        {{ __("Order Statistics") }}
                                    </h2>

                                    <div class="space-y-4">
                                        <div
                                            class="flex justify-between items-center pb-3 border-b border-gray-100"
                                        >
                                            <span
                                                class="font-medium text-gray-700"
                                                >{{ __("Total Orders") }}:</span
                                            >
                                            <span
                                                class="text-gray-900 font-bold text-lg"
                                                >{{
                                                    item.checkouts_count
                                                }}</span
                                            >
                                        </div>

                                        <div class="space-y-3">
                                            <span
                                                class="font-medium text-gray-700 block"
                                                >{{
                                                    __("Totals by Currency")
                                                }}:</span
                                            >
                                            <div
                                                v-for="(
                                                    c, idx
                                                ) in item.checkout"
                                                :key="idx"
                                                class="flex justify-between items-center bg-gray-50 rounded-lg px-4 py-2"
                                            >
                                                <span
                                                    class="font-medium text-gray-700"
                                                    >{{ c.currency }}</span
                                                >
                                                <span
                                                    class="text-green-600 font-bold"
                                                    >{{ c.total }}</span
                                                >
                                            </div>
                                            <div
                                                v-if="
                                                    !item.checkout ||
                                                    item.checkout.length === 0
                                                "
                                                class="text-gray-500 text-center py-4"
                                            >
                                                {{
                                                    __(
                                                        "No currency data available"
                                                    )
                                                }}
                                            </div>
                                        </div>

                                        <!-- Progress Bar -->
                                        <div class="mt-6">
                                            <div
                                                class="flex justify-between items-center mb-2"
                                            >
                                                <span
                                                    class="font-medium text-gray-700"
                                                    >{{
                                                        __("Order Progress")
                                                    }}</span
                                                >
                                                <span
                                                    class="text-sm text-gray-600"
                                                    >{{ item.checkouts_count }}
                                                    {{ __("Orders") }}</span
                                                >
                                            </div>
                                            <div
                                                class="w-full bg-gray-200 rounded-full h-4"
                                            >
                                                <div
                                                    class="bg-gradient-to-r from-blue-500 to-purple-600 h-4 rounded-full transition-all duration-500"
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
                                            <div
                                                class="text-xs text-gray-500 mt-1 text-center"
                                            >
                                                {{
                                                    __(
                                                        "Progress based on order activity"
                                                    )
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="px-6 py-4 bg-gray-50 border-t border-gray-200"
                        >
                            <div class="flex flex-wrap gap-3 justify-end">
                                <a
                                    :href="`mailto:${item.email}`"
                                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-all duration-300 flex items-center space-x-2 shadow-sm hover:shadow-md"
                                >
                                    <i class="fas fa-envelope"></i>
                                    <span>{{ __("Send Email") }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="flex justify-center mt-8">
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

<script>
import VAdminLayout from "../../Components/VAdminLayout.vue";
import VPaginate from "@/components/VPaginate.vue";

export default {
    components: {
        VAdminLayout,
        VPaginate,
    },

    data() {
        return {
            loading: true,
            customers: [],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
        };
    },

    created() {
        this.getCustomer();
    },

    methods: {
        customerInitials(item) {
            if (item.name && item.last_name) {
                return item.name.charAt(0) + item.last_name.charAt(0);
            }
            return "CU";
        },

        async getCustomer() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes.customers,
                    { params: this.search }
                );

                if (res.status == 200) {
                    this.customers = res.data.data;
                    let meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
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
