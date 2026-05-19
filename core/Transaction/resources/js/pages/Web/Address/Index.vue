<template>
    <v-main-layout>
        <v-head
            :title="__('Delivery Addresses')"
            :description="__('Manage your saved delivery addresses')"
        >
            <template #actions>
                <v-create @created="getDeliveryAddresses" />
            </template>
        </v-head>

        <v-table
            :items="addresses"
            :loading="loading"
            :per-page="search.per_page"
            :show-pagination="false"
            :empty-text="__('No addresses found. Add your first delivery address.')"
            empty-icon="mdi-map-marker-off-outline"
            loading-text="Loading addresses..."
            table-class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
            thead-class="bg-gray-50 dark:bg-gray-700"
            tbody-class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
        >
            <template #head>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        {{ __('Full Name') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        {{ __('Address') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        {{ __('City / State') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        {{ __('Phone') }}
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        {{ __('Actions') }}
                    </th>
                </tr>
            </template>

            <template #default="{ items }">
                <tr
                    v-for="address in items"
                    :key="address.id"
                    class="transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-gray-700/50"
                >
                    <!-- Full Name -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center shrink-0">
                                <i class="mdi mdi-account text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ address.full_name }}
                                </p>
                                <p v-if="address.references" class="text-xs text-gray-500 dark:text-gray-400 italic max-w-[200px] truncate" :title="address.references">
                                    {{ __('Ref') }}: {{ address.references }}
                                </p>
                            </div>
                        </div>
                    </td>

                    <!-- Address -->
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 dark:text-gray-100 max-w-[250px]">
                            <p class="truncate" :title="address.address">
                                <i class="mdi mdi-map-marker text-gray-400 dark:text-gray-500 text-xs mr-1"></i>
                                {{ address.address }}
                            </p>
                            <p v-if="address.address_line_2" class="text-xs text-gray-500 dark:text-gray-400 truncate" :title="address.address_line_2">
                                {{ address.address_line_2 }}
                            </p>
                            <p v-if="address.postal_code" class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                <i class="mdi mdi-mailbox-outline text-xs mr-1"></i>
                                {{ address.postal_code }}
                            </p>
                        </div>
                    </td>

                    <!-- City / State -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-gray-100">
                            <p class="font-medium">
                                <i class="mdi mdi-city text-gray-400 dark:text-gray-500 text-xs mr-1"></i>
                                {{ address.city }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ address.district }}, {{ address.state }}
                            </p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                <i class="mdi mdi-earth text-xs mr-1"></i>
                                {{ address.country }}
                            </p>
                        </div>
                    </td>

                    <!-- Phone -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-gray-100">
                            <p class="font-medium">
                                <i class="mdi mdi-phone text-gray-400 dark:text-gray-500 text-xs mr-1"></i>
                                {{ address.phone }}
                            </p>
                            <p v-if="address.secondary_phone" class="text-xs text-gray-500 dark:text-gray-400">
                                <i class="mdi mdi-phone-plus-outline text-xs mr-1"></i>
                                {{ address.secondary_phone }}
                            </p>
                        </div>
                    </td>

                    <!-- Actions -->
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end gap-2">
                            <v-create
                                :item="address"
                                @updated="getDeliveryAddresses"
                            />
                            <v-delete
                                :item="address"
                                @deleted="getDeliveryAddresses"
                            />
                        </div>
                    </td>
                </tr>
            </template>
        </v-table>

        <!-- Empty State -->
        <div
            v-if="!loading && addresses.length === 0"
            class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-700"
        >
            <span class="mdi mdi-map-marker-outline text-gray-400 dark:text-gray-600 text-5xl"></span>
            <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">
                {{ __('No addresses found') }}
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __("You haven't added any delivery addresses yet.") }}
            </p>
        </div>

        <!-- Pagination -->
        <v-paginate
            :total-pages="pages.total_pages"
            v-model="search.page"
            @change="getDeliveryAddresses"
        />
    </v-main-layout>
</template>

<script setup>
import VMainLayout  from "@/components/VMainLayout.vue";
import VHead from "@/components/VHead.vue";
import VTable from "@/components/VTable.vue";
import { usePage } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import VCreate from "./VCreate.vue";
import VDelete from "./VDelete.vue";
import VPaginate from "@/components/VPaginate.vue";

const page = usePage();

const addresses = ref([]);
const loading = ref(false);
const pages = ref({
    total_pages: 0,
});

const search = ref({
    per_page: 15,
    page: 1,
});

const getDeliveryAddresses = async () => {
    loading.value = true;
    try {
        const res = await $server.get(page.props.api.address, {
            params: {
                per_page: search.value.per_page,
                page: search.value.page,
            },
        });
        if (res.status == 200) {
            const values = res.data;
            addresses.value = values.data;
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

onMounted(async () => {
    await getDeliveryAddresses();
});
</script>