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
            :title="__('Partner Management')"
            :description="__('Manage your partners and their commissions')"
        >
            <template #bottom>
                <div class="flex items-center justify-between mb-4">
                    <h2
                        class="flex-1 text-base sm:text-lg font-semibold text-gray-900 dark:text-white transition-colors duration-200"
                    >
                        {{ __("Filters") }}
                    </h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <v-input :label="__('Name')" v-model="search.name" />

                    <v-input
                        :label="__('Last name')"
                        v-model="search.last_name"
                    />

                    <v-input :label="__('Email')" v-model="search.last_name" />

                    <v-input :label="__('Code')" v-model="search.code" />

                    <v-select
                        :label="__('Choose pagination')"
                        v-model="search.per_page"
                        @change="searcher"
                        :options="[
                            { name: 15, id: 15 },
                            { name: 50, id: 50 },
                            { name: 100, id: 100 },
                            { name: 150, id: 150 },
                            { name: 200, id: 200 },
                            { name: 300, id: 300 },
                        ]"
                    />

                    <div class="flex items-end justify-around">
                        <v-button
                            @click="searcher"
                            :label="__('Search')"
                            left-icon="mdi mdi-search"
                            size="md"
                        />

                        <v-button
                            @click="resetFilters"
                            :label="__('Clear')"
                            left-icon="mdi mdi-filter-remove"
                            variant="secondary"
                            size="md"
                        />
                    </div>
                </div>
            </template>
        </v-head>

        <div class="mb-6">
            <v-table
                :items="users"
                :loading="loading"
                :per-page="search.per_page"
                :show-pagination="false"
                :empty-text="__('Theres no found partnes')"
                empty-icon="mdi mdi-account-off-outline"
                loading-text="Loading users..."
                table-class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
                thead-class="bg-gray-50 dark:bg-gray-700"
                tbody-class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
            >
                <template #head>
                    <tr>
                        <th
                            v-for="(column, index) in columns"
                            :key="index"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                        >
                            {{ __(column) }}
                        </th>
                    </tr>
                </template>

                <template #default="{ items }">
                    <tr
                        v-for="user in users"
                        :key="user.id"
                        class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200"
                    >
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-semibold text-sm mr-3 transition-colors duration-200"
                                >
                                    {{ userInitials(user) }}
                                </div>
                                <div>
                                    <div
                                        class="text-sm font-medium text-gray-900 dark:text-white transition-colors duration-200"
                                    >
                                        {{ user.name }}
                                        {{ user.last_name }}
                                    </div>
                                    <div
                                        class="text-sm text-gray-500 dark:text-gray-400 transition-colors duration-200"
                                    >
                                        {{ user.email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td
                            class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white transition-colors duration-200"
                        >
                            {{ user.code }}
                        </td>
                        <td
                            class="px-4 sm:px-6 py-4 whitespace-nowrap text-center"
                        >
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800 transition-colors duration-200"
                            >
                                {{ user.commission_rate }}%
                            </span>
                        </td>
                        <td
                            class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                        >
                            <v-update :item="user" @updated="getPartners" />
                        </td>
                    </tr>
                </template>
            </v-table>
        </div>

        <!-- Pagination -->
        <v-paginate
            v-if="pages.total_pages > 1"
            :total-pages="pages.total_pages"
            v-model="search.page"
            @change="getPartners"
        />
    </v-main-layout>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import VUpdate from "./Update.vue";
import VMainLayout from "@/components/VMainLayout.vue";
import VHead from "@/components/VHead.vue";
import VButton from "@/components/VButton.vue";
import VInput from "@/components/VInput.vue";
import VPaginate from "@/components/VPaginate.vue";
import VSelect from "@/components/VSelect.vue";
import VTable from "@/components/VTable.vue";
import { useForm, usePage } from "@inertiajs/vue3";

const page = usePage();
const loading = ref(false);
const users = ref([]);

const pages = ref({
    total_pages: 0,
    total_count: 0,
});

const columns = ["Parner", "Code", "Comission rate", "Actions"];

const search = useForm({
    page: 1,
    per_page: 15,
    name: "",
    last_name: "",
    email: "",
    code: "",
});

const userInitials = (user) => {
    return (
        `${user.name?.charAt(0) || ""}${
            user.last_name?.charAt(0) || ""
        }`.toUpperCase() || "U"
    );
};

const loadData = (data) => {
    users.value = data.data;
    pages.value = data.meta.pagination;
};

const getPartners = () => {
    loading.value = true;

    search.get(page.props.routes.partners, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (res) => {
            loadData(res.props.data);
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};

const resetFilters = () => {
    search.page = 1;
    search.per_page = 15;
    search.name = "";
    search.last_name = "";
    search.email = "";
    search.code = "";
    getPartners();
};

const searcher = () => {
    search.page = 1;
    getPartners();
};

onMounted(() => {
    loadData(page.props.data);
});
</script>
