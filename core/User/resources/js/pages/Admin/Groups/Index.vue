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
        <!-- Header Section -->
        <v-head
            :title="__('Groups Management')"
            :description="__('Manage user groups and permissions')"
        >
            <template #actions>
                <v-create @created="getGroups" />
            </template>
            <template #bottom>
                <div
                    class="filters-card bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 mb-6 shadow-sm"
                >
                    <div class="p-6">
                        <div
                            class="text-xl font-medium text-gray-800 dark:text-white mb-4 flex items-center"
                        >
                            <i class="mdi mdi-filter mr-2 text-blue-500"></i>
                            {{ __("Filter Groups") }}
                        </div>
                        <div class="flex justify-around gap-2">
                            <div
                                class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-2 items-end"
                            >
                                <v-input
                                    :label="__('Group Name')"
                                    v-model="search.name"
                                    @input="getGroups"
                                />

                                <v-select
                                    :label="__('Choose pagination')"
                                    v-model="search.per_page"
                                    @change="getGroups"
                                    :options="[
                                        { name: __('All Types'), id: '' },
                                        { name: __('System Groups'), id: 1 },
                                        { name: __('User Groups'), id: 0 },
                                    ]"
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
                                <div
                                    class="flex shrink-0 flex-nowrap justify-around"
                                >
                                    <v-button
                                        :label="__('Search')"
                                        left-icon="mdi mdi-search"
                                        @click="getGroups"
                                        size="xs"
                                    />

                                    <v-button
                                        @click="resetFilters"
                                        :label="__('Reset Filters')"
                                        left-icon="mdi mdi-refresh"
                                        variant="secondary"
                                        size="xs"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </v-head>

        <div class="groups-list mb-6">
            <div
                class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden"
            >
                <v-table
                    :items="groups"
                    :loading="loading"
                    :per-page="Number(search.per_page)"
                    :show-pagination="false"
                    :empty-text="__('Try adjusting your search filters')"
                    empty-icon="mdi-account-group-off"
                    loading-text="Loading groups..."
                    table-class="min-w-[920px] w-full divide-y divide-gray-200 dark:divide-gray-700"
                    thead-class="bg-white dark:bg-gray-700"
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
                            v-for="group in items"
                            :key="group.id"
                            class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-700/50"
                        >
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div
                                    class="font-medium text-gray-900 dark:text-white"
                                >
                                    {{ __(group.name) }}
                                </div>
                            </td>

                            <td
                                class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400"
                            >
                                <div class="max-w-xs xl:max-w-md">
                                    {{ __(group.description) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                    :class="
                                        group.system
                                            ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300'
                                            : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300'
                                    "
                                >
                                    <i
                                        :class="
                                            group.system
                                                ? 'mdi mdi-shield-check'
                                                : 'mdi mdi-account'
                                        "
                                        class="mr-1 text-sm"
                                    ></i>
                                    {{
                                        group.system ? __("System") : __("User")
                                    }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex justify-end gap-2">
                                    <v-create
                                        @updated="getGroups"
                                        :item="group"
                                    />
                                    <v-delete
                                        v-if="!group.system"
                                        @deleted="getGroups"
                                        :item="group"
                                    />
                                </div>
                            </td>
                        </tr>
                    </template>
                </v-table>
            </div>
        </div>

        <!-- Pagination -->
        <v-paginate
            :total-pages="pages.total_pages"
            v-model="search.page"
            @change="getGroups"
        />
    </v-main-layout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import VButton from "@/components/VButton.vue";
import VMainLayout from "@/components/VMainLayout.vue";
import VPaginate from "@/components/VPaginate.vue";
import VTable from "@/components/VTable.vue";
import VHead from "@/components/VHead.vue";
import VSelect from "@/components/VSelect.vue";
import VInput from "@/components/VInput.vue";
import VCreate from "./Create.vue";
import VDelete from "./Delete.vue";

const page = usePage();

const groups = ref([]);
const loading = ref(false);
const pages = ref({ total_pages: 0 });
const search = useForm({
    page: 1,
    per_page: 15,
    name: "",
    system: null, //true | false | null
    order_type: "desc",
});

const columns = ref(["Group name", "Description", "Type", "Actions"]);

onMounted(async () => {
    loadData(page.props.data);
});

const loadData = (data) => {
    groups.value = data.data;
    pages.value = data.meta.pagination;
};

const changePage = () => {
    getGroups();
};

const getGroups = async () => {
    loading.value = true;

    search.get(page.props.routes.groups, {
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

const resetFilters = () => {
    search.resetAndClearErrors();
    getGroups();
};
</script>
