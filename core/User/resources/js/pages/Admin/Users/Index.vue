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
            :title="__('User Management')"
            :description="__('Manage system users and their permissions')"
        >
            <template #actions>
                <v-button
                    as="a"
                    :to="page.props.routes.create"
                    :label="__('New user')"
                    icon="mdi mdi-plus-circle"
                    size="md"
                    variant="secondary"
                />
            </template>
            <template #bottom>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-2 mb-4">
                    <v-input
                        :label="__('Name')"
                        v-model="search.name"
                        @input="debouncedSearch"
                        :placeholder="__('Enter name...')"
                    />

                    <v-input
                        :label="__('Last Name')"
                        v-model="search.last_name"
                        @input="debouncedSearch"
                        :placeholder="__('Enter last name ...')"
                    />

                    <v-input
                        :label="__('Email')"
                        v-model="search.email"
                        @input="debouncedSearch"
                        :placeholder="__('Enter email ...')"
                    />

                    <v-select
                        :label="__('Choose pagination')"
                        v-model="search.per_page"
                        @change="changePage"
                        :options="[
                            { name: 15, id: 15 },
                            { name: 50, id: 50 },
                            { name: 100, id: 100 },
                            { name: 150, id: 150 },
                            { name: 200, id: 200 },
                            { name: 300, id: 300 },
                        ]"
                    />
                    <div class="flex items-end justify-end">
                        <v-button
                            :label="__('Reset')"
                            variant="primary"
                            size="md"
                            @click="clearFilters"
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
                :empty-text="
                    __('Try adjusting your filters or create a new user')
                "
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
                            {{ column }}
                        </th>
                    </tr>
                </template>

                <template #default="{ items }">
                    <tr
                        v-for="user in items"
                        :key="user.id"
                        class="transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-gray-700/50"
                    >
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div
                                class="font-bold text-blue-600 dark:text-blue-400"
                            >
                                {{ user.name }}
                                {{ user.last_name }}
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ user.email }}
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span
                                :class="[
                                    'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium transition-colors duration-200',
                                    user.disabled
                                        ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-800 dark:text-orange-300'
                                        : 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300',
                                ]"
                            >
                                <i
                                    :class="[
                                        'mdi mr-1 text-sm',
                                        user.disabled
                                            ? 'mdi-close-circle text-orange-600 dark:text-orange-400'
                                            : 'mdi-check-circle text-green-600 dark:text-green-400',
                                    ]"
                                ></i>
                                {{
                                    user.disabled
                                        ? __("Inactive")
                                        : __("Active")
                                }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex justify-end space-x-2">
                                <v-button
                                    as="a"
                                    :to="user.links.show"
                                    icon="mdi mdi-eye"
                                    size="md"
                                    round
                                    variant="success"
                                    :title="__('Update user')"
                                />
                               
                                <v-status :item="user" @updated="getUsers" />
                            </div>
                        </td>
                    </tr>
                </template>
            </v-table>
        </div>

        <!-- Pagination -->
        <v-paginate
            v-model="search.page"
            :total-pages="pages.total_pages"
            @change="getUsers"
        />
    </v-main-layout>
</template>

<script setup>
import VMainLayout from "@/components/VMainLayout.vue";
import VHead from "@/components/VHead.vue";
import VButton from "@/components/VButton.vue";
import VInput from "@/components/VInput.vue";
import VSelect from "@/components/VSelect.vue";
import VPaginate from "@/components/VPaginate.vue";
import VTable from "@/components/VTable.vue"; 
import VStatus from "./Status.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { ref, reactive, onMounted, computed } from "vue";

const page = usePage();

const loading = ref(false);
const columns = reactive([
    __("Name"),
    __("Email"),
    __("Status"),
    __("Actions"),
]);
const users = ref([]);
const pages = ref({
    total_pages: 0,
});

const search = useForm({
    page: 1,
    per_page: 15,
    name: "",
    last_name: "",
    email: "",
});

// Search timeout for debouncing
const searchTimeout = ref(null);

onMounted(() => {
    loadData(page.props.data);
});

// Methods

const changePage = () => {
    getUsers();
};

const debouncedSearch = () => {
    clearTimeout(searchTimeout.value);
    searchTimeout.value = setTimeout(async () => {
        search.page = 1;
        await getUsers();
    }, 500);
};

const clearFilters = async () => {
    search.resetAndClearErrors();
    getUsers();
};

const loadData = (data) => {
    users.value = data.data;
    pages.value = data.meta.pagination;
};

const getUsers = async () => {
    loading.value = true;

    search.get(page.props.routes.users, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (res) => {
            loadData(res.props.data);
        },
        onError: (e) => {
            console.log(e);
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};
</script>
