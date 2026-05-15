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
    <v-main-layout>
        <v-head
            :title="__('API Keys Management')"
            :description="
                __('Manage your API keys for secure application integration')
            "
        >
            <template #actions>
                <v-create @created="getPersonalAccessToken" />
            </template>
        </v-head>

        <v-table
            :items="tokens"
            :loading="loading"
            :per-page="search.per_page"
            :show-pagination="false"
            :empty-text="__('Create your first API Token')"
            empty-icon="mdi-file-document-outline"
            loading-text="Loading tokens..."
            table-class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
            thead-class="bg-gray-50 dark:bg-gray-700/50"
            tbody-class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
        >
            <template #head>
                <tr>
                    <th
                        scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider transition-colors duration-200"
                    >
                        {{ __("Key Name") }}
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider transition-colors duration-200"
                    >
                        {{ __("Created Date") }}
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider transition-colors duration-200"
                    >
                        {{ __("Expiration Date") }}
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider transition-colors duration-200"
                    >
                        {{ __("Actions") }}
                    </th>
                </tr>
            </template>

            <template #default="{ items }">
                <tr
                    v-for="item in items"
                    :key="item.id"
                    class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 group"
                >
                    <!-- Name -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div
                                class="shrink-0 h-10 w-10 flex items-center justify-center bg-indigo-50 dark:bg-indigo-900/30 rounded-lg transition-colors duration-200 group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/50"
                            >
                                <svg
                                    class="h-5 w-5 text-indigo-600 dark:text-indigo-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"
                                    />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div
                                    class="text-sm font-medium text-gray-900 dark:text-white"
                                >
                                    {{ item.name }}
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Created Date -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white">
                            {{ formatDate(item.created) }}
                        </div>
                        <div
                            class="flex items-center mt-1 text-xs text-gray-500 dark:text-gray-400"
                        >
                            <svg
                                class="shrink-0 mr-1.5 h-4 w-4 text-gray-400 dark:text-gray-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />
                            </svg>
                            {{ formatTimeAgo(item.created) }}
                        </div>
                    </td>

                    <!-- Expiration Date -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div
                            :class="[
                                'text-sm font-medium',
                                getExpirationClass(item.expires) ===
                                'text-positive'
                                    ? 'text-green-600 dark:text-green-400'
                                    : getExpirationClass(item.expires) ===
                                        'text-warning'
                                      ? 'text-amber-600 dark:text-amber-400'
                                      : 'text-red-600 dark:text-red-400',
                            ]"
                        >
                            {{
                                item.expires
                                    ? formatDate(item.expires)
                                    : __("Never")
                            }}
                        </div>
                        <div class="flex items-center mt-1">
                            <svg
                                :class="[
                                    'shrink-0 mr-1.5 h-4 w-4',
                                    getExpirationClass(item.expires) ===
                                    'text-positive'
                                        ? 'text-green-500 dark:text-green-400'
                                        : getExpirationClass(item.expires) ===
                                            'text-warning'
                                          ? 'text-amber-500 dark:text-amber-400'
                                          : 'text-red-500 dark:text-red-400',
                                ]"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    :d="getExpirationIcon(item.expires)"
                                />
                            </svg>
                            <span
                                :class="[
                                    'text-xs font-medium',
                                    getExpirationClass(item.expires) ===
                                    'text-positive'
                                        ? 'text-green-600 dark:text-green-400'
                                        : getExpirationClass(item.expires) ===
                                            'text-warning'
                                          ? 'text-amber-600 dark:text-amber-400'
                                          : 'text-red-600 dark:text-red-400',
                                ]"
                            >
                                {{ getExpirationStatus(item.expires) }}
                            </span>
                        </div>
                        <div
                            v-if="isExpiringSoon(item.expires)"
                            class="text-xs text-amber-600 dark:text-amber-400 mt-1 flex items-center"
                        >
                            <svg
                                class="shrink-0 mr-1 h-3 w-3"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            {{ __("Expiring soon") }}
                        </div>
                    </td>

                    <!-- Actions -->
                    <td
                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                    >
                        <div class="flex justify-end items-center space-x-2">
                            <v-delete
                                @deleted="getPersonalAccessToken"
                                :item="item"
                            />
                        </div>
                    </td>
                </tr>
            </template>
        </v-table>

        <!-- Pagination -->
        <v-paginate
            v-model="search.page"
            :total-pages="pages.total_pages"
            @change="getPersonalAccessToken"
        />
    </v-main-layout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import VMainLayout from "@/components/VMainLayout.vue";
import VPaginate from "@/components/VPaginate.vue";
import VTable from "@/components/VTable.vue";
import VHead from "@/components/VHead.vue";
import VCreate from "./Create.vue";
import VDelete from "./Delete.vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const tokens = ref([]);
const loading = ref(false);
const pages = ref({
    total_pages: 0,
});
const search = ref({
    page: 1,
    per_page: 15,
});

onMounted(async () => {
    await getPersonalAccessToken();
});

const getPersonalAccessToken = async () => {
    loading.value = true;
    try {
        const res = await $server.get(page.props.route, {
            params: search.value,
        });

        if (res.status == 200) {
            const values = res.data;
            tokens.value = values.data;
            pages.value = values.meta.pagination;
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    } finally {
        loading.value = false;
    }
};

const getExpirationClass = (expirationDate) => {
    if (!expirationDate) return "text-positive";

    const expDate = new Date(expirationDate);
    const now = new Date();
    const sevenDaysFromNow = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000);

    if (expDate < now) return "text-negative";
    if (expDate < sevenDaysFromNow) return "text-warning";
    return "text-positive";
};

const getExpirationIcon = (expirationDate) => {
    if (!expirationDate)
        return "M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z";

    const expDate = new Date(expirationDate);
    const now = new Date();

    if (expDate < now)
        return "M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z";
    return "M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z";
};

const getExpirationStatus = (expirationDate) => {
    if (!expirationDate) return __("Never expires");

    const expDate = new Date(expirationDate);
    const now = new Date();

    if (expDate < now) return __("Expired");

    const sevenDaysFromNow = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000);
    if (expDate < sevenDaysFromNow) return __("Expires soon");

    return __("Active");
};

const isExpiringSoon = (expirationDate) => {
    if (!expirationDate) return false;

    const expDate = new Date(expirationDate);
    const now = new Date();
    const sevenDaysFromNow = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000);

    return expDate > now && expDate < sevenDaysFromNow;
};

const formatDate = (dateString) => {
    if (!dateString) return "N/A";
    const date = new Date(dateString);
    return date.toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};

const formatTimeAgo = (dateString) => {
    if (!dateString) return "";

    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now - date);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays === 1) return __("1 day ago");
    if (diffDays < 7) return __(":count days ago", { count: diffDays });
    if (diffDays < 30)
        return __(":count weeks ago", {
            count: Math.floor(diffDays / 7),
        });
    return __(":count months ago", {
        count: Math.floor(diffDays / 30),
    });
};

const copyToClipboard = (text) => {
    navigator.clipboard
        .writeText(text)
        .then(() => {
            $notify.success(__("API key copied to clipboard"));
        })
        .catch(() => {
            $notify.error(__("Failed to copy to clipboard"));
        });
};
</script>
