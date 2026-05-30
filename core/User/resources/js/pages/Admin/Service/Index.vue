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
            :title="__('Services Management')"
            :description="__('Manage and organize your application services')"
        >
            <template #actions>
                <v-create @created="getServices" />
            </template>
            <template #bottom>
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4"
                >
                    <v-input
                        :label="__(' Name')"
                        v-model="search.name"
                        @input="getServices"
                    />

                    <v-select
                        :label="__('Filter by Group')"
                        v-model="search.group"
                        @change="getServices"
                        :options="groups"
                        label-key="name"
                        value-key="slug"
                    />

                    <v-select
                        :label="__('Filter by Visibility')"
                        v-model="search.visibility"
                        @change="getServices"
                        :options="[
                            { name: __('All'), id: '' },
                            { name: __('Public'), id: 'public' },
                            { name: __('Private'), id: 'private' },
                            { name: __('Internal'), id: 'internal' },
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

                    <div class="flex justify-center items-end">
                        <v-button
                            @click="clearFilters"
                            :label="__('Clear Filters')"
                            left-icon="mdi mdi-filter-remove"
                            variant="light"
                        />
                    </div>
                </div>
            </template>
        </v-head>

        <div class="mb-6">
            <div
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm overflow-hidden transition-colors duration-200"
            >
                <v-table
                    :items="services"
                    :loading="loading"
                    :per-page="Number(search.per_page)"
                    :show-pagination="false"
                    :empty-text="
                        __('Try adjusting your filters or create a new service')
                    "
                    empty-icon="mdi-cog-off"
                    loading-text="Loading services..."
                    table-class="min-w-[1080px] w-full divide-y divide-gray-200 dark:divide-gray-700"
                    thead-class="bg-white dark:bg-gray-800"
                    tbody-class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
                >
                    <template #head>
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                            >
                                {{ __("Service") }}
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                            >
                                {{ __("Group") }}
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                            >
                                {{ __("Description") }}
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                            >
                                {{ __("System") }}
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                            >
                                {{ __("Visibility") }}
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                            >
                                {{ __("Actions") }}
                            </th>
                        </tr>
                    </template>

                    <template #default="{ items }">
                        <tr
                            v-for="service in items"
                            :key="service.id"
                            class="transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-gray-700/50"
                        >
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div
                                    class="font-semibold text-blue-600 dark:text-blue-400"
                                >
                                    {{ __(service.name) }}
                                </div>
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                            >
                                {{ __(service.group.name) }}
                            </td>
                            <td
                                class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400"
                            >
                                <div class="max-w-xs xl:max-w-md">
                                    {{ __(service.description) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="[
                                        'inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium transition-colors duration-200',
                                        service.system
                                            ? 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300'
                                            : 'bg-orange-100 dark:bg-orange-900/40 text-orange-800 dark:text-orange-300',
                                    ]"
                                >
                                    <i
                                        :class="
                                            service.system
                                                ? 'mdi mdi-shield-check'
                                                : 'mdi mdi-cog'
                                        "
                                    ></i>
                                    {{ service.system ? __("Yes") : __("No") }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800 transition-colors duration-200 dark:bg-blue-900/40 dark:text-blue-300"
                                >
                                    <i class="mdi mdi-eye"></i>
                                    {{ __(service.visibility) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex justify-end gap-2">
                                    <v-button
                                        icon="mdi mdi-shield-lock-open-outline"
                                        title="Manage Scopes"
                                        round
                                        variant="secondary"
                                        as="a"
                                        :to="service.links.scopes"
                                    />
                                    <v-create
                                        :item="service"
                                        @updated="getServices"
                                    />
                                    <v-delete
                                        v-if="!service.system"
                                        :item="service"
                                        @deleted="getServices"
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
            v-model="search.page"
            :total-pages="pages.total_pages"
            @change="getServices"
        />
    </v-main-layout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import VButton from "@/components/VButton.vue";
import VMainLayout from "@/components/VMainLayout.vue";
import VPaginate from "@/components/VPaginate.vue";
import VTable from "@/components/VTable.vue";
import VCreate from "./Create.vue";
import VDelete from "./Delete.vue";
import VHead from "@/components/VHead.vue";
import VInput from "@/components/VInput.vue";
import VSelect from "@/components/VSelect.vue";
import { useForm, usePage } from "@inertiajs/vue3";

const page = usePage();
/**
 * State
 */
const services = ref([]);
const loading = ref(false);
const pages = ref({
    total_pages: 0,
});

const search = useForm({
    page: 1,
    per_page: 15,
    name: "",
    group: "",
    visibility: "",
    system: "",
});

const groups = ref([]);

onMounted(async () => {
    loadData(page.props.data);
    await getGroups();
});

/**
 * Methods
 */
const changePage = () => {
    getServices();
};

const clearFilters = () => {
    search.resetAndClearErrors();
    getServices();
};

const loadData = (data) => {
    services.value = data.data;
    pages.value = data.meta.pagination;
};

const getServices = () => {
    loading.value = true;

    search.get(page.props.routes.services, {
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

const getGroups = async () => {
    try {
        const res = await $server.get(page.props.api.groups, {
            params: {
                per_page: 100,
            },
        });
        if (res.status == 200) {
            const values = res.data;
            groups.value = values.data;
        }
    } catch (error) {
        if (error?.response?.data?.message) {
            $notify.error(error.response.data.message);
        }
    } finally {
        loading.value = false;
    }
};
</script>
