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
    <v-account-layout>
         
            <v-head
                :title="__('Roles Management')"
                :description="__('Manage user roles and permissions')"
            >
                <template #actions>
                    <v-create @created="getRoles" />
                </template>
                <template #bottom>
                    <div
                        class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-4"
                    >
                        <v-input
                            :label="__('Name')"
                            v-model="search.name"
                            @input="getRoles"
                        />

                        <v-select
                            :label="__('Role type')"
                            v-model="search.system"
                            @change="getRoles"
                            :options="[
                                { name: __('All roles'), id: '' },
                                { name: __('System roles only'), id: true },
                                { name: __('Custom roles only'), id: false },
                            ]"
                        />

                        <!-- Results Per Page -->
                        <v-select
                            :label="__('Results per page')"
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

                        <!-- Clear Filters -->
                        <div class="flex items-end">
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
                        :items="roles"
                        :loading="loading"
                        :per-page="Number(search.per_page)"
                        :show-pagination="false"
                        :empty-text="
                            __(
                                'Try adjusting your filters or create a new role',
                            )
                        "
                        empty-icon="mdi-account-off-outline"
                        loading-text="Loading roles..."
                        table-class="min-w-[860px] w-full divide-y divide-gray-200 dark:divide-gray-700"
                        thead-class="bg-white dark:bg-gray-800"
                        tbody-class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
                    >
                        <template #head>
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                                >
                                    {{ __("Role") }}
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                                >
                                    {{ __("Description") }}
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                                >
                                    {{ __("Type") }}
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
                                v-for="role in items"
                                :key="role.id"
                                class="transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-gray-700/50"
                            >
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div
                                        class="font-semibold text-gray-900 dark:text-white"
                                    >
                                        {{ role.name }}
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400"
                                >
                                    <div class="max-w-xs xl:max-w-md">
                                        {{ __(role.description) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        :class="[
                                            'inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium transition-colors duration-200',
                                            role.system
                                                ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-800 dark:text-orange-300'
                                                : 'bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300',
                                        ]"
                                    >
                                        <i
                                            :class="
                                                role.system
                                                    ? 'mdi mdi-shield-check'
                                                    : 'mdi mdi-account-cog'
                                            "
                                        ></i>
                                        {{
                                            role.system
                                                ? __("System")
                                                : __("User")
                                        }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex justify-end gap-2">
                                        <v-create
                                            @updated="getRoles"
                                            :item="role"
                                        />
                                        <v-delete
                                            v-if="!role.system"
                                            @deleted="getRoles"
                                            :item="role"
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
                @change="getRoles"
            /> 
    </v-account-layout>
</template>

<script setup>
import VAccountLayout from "@/components/VAccountLayout.vue"; 
import VPaginate from "@/components/VPaginate.vue";
import VButton from "@/components/VButton.vue";
import VTable from "@/components/VTable.vue";
import VCreate from "./Create.vue";
import VDelete from "./Delete.vue";
import VHead from "@/components/VHead.vue";
import VInput from "@/components/VInput.vue";
import VSelect from "@/components/VSelect.vue";
import { usePage } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";

const page = usePage();

const roles = ref([]);
const loading = ref(false);
const pages = ref({
    total_pages: 0,
});

const search = ref({
    page: 1,
    per_page: 15,
    name: "",
    system: "",
    order_type: "desc",
});
// mounted
onMounted(async () => {
    await getRoles();
});

// methods
const clearFilters = () => {
    search.value = {
        page: 1,
        per_page: 15,
        name: "",
        system: "",
        order_type: "desc",
    };
    getRoles();
};

const changePage = () => {
    search.page = 1;
    getRoles();
};

const getRoles = async () => {
    loading.value = true;

    try {
        const res = await $server.get(page.props.api.roles, {
            params: search.value,
        });
        if (res.status == 200) {
            const values = res.data;
            roles.value = values.data;
            pages.value = values.meta.pagination;
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
