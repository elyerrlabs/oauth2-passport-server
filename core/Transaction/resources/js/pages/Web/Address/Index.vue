<template>
    <v-account-layout>
        <!-- Header -->
        <div class="mb-8 flex justify-between">
            <div>
                <h2
                    class="text-2xl font-semibold text-gray-900 dark:text-gray-100"
                >
                    {{ __("Delivery Addresses") }}
                </h2>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    {{ __("Manage your saved delivery addresses") }}
                </p>
            </div>
            <v-create @created="getDeliveryAddresses" />
        </div>

        <!-- Addresses Grid -->
        <div
            v-if="addresses.length > 0"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
        >
            <div
                v-for="address in addresses"
                :key="address.id"
                class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-200 dark:border-gray-700 overflow-hidden"
            >
                <!-- Card Header -->
                <div
                    class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center"
                >
                    <h3
                        class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                    >
                        {{ address.full_name }}
                    </h3>
                    <div class="flex space-x-2">
                        <v-create
                            :item="address"
                            @updated="getDeliveryAddresses"
                        />

                        <v-delete
                            :item="address"
                            @deleted="getDeliveryAddresses"
                        />
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-4 space-y-3">
                    <!-- Address -->
                    <div
                        class="flex items-start space-x-2 text-gray-700 dark:text-gray-300"
                    >
                        <span
                            class="mdi mdi-map-marker text-gray-400 dark:text-gray-500 text-xl shrink-0 mt-0.5"
                        ></span>
                        <span class="text-sm flex-1">
                            {{ address.address }}
                            <span
                                v-if="address.address_line_2"
                                class="text-gray-500 dark:text-gray-400"
                                >, {{ address.address_line_2 }}</span
                            >
                        </span>
                    </div>

                    <!-- Location -->
                    <div
                        class="flex items-start space-x-2 text-gray-700 dark:text-gray-300"
                    >
                        <span
                            class="mdi mdi-city text-gray-400 dark:text-gray-500 text-xl shrink-0 mt-0.5"
                        ></span>
                        <span class="text-sm flex-1">
                            {{ address.district }}, {{ address.city }},
                            {{ address.state }}, {{ address.country }}
                            <span
                                v-if="address.postal_code"
                                class="text-gray-500 dark:text-gray-400"
                            >
                                - {{ address.postal_code }}</span
                            >
                        </span>
                    </div>

                    <!-- Phones -->
                    <div
                        class="flex items-start space-x-2 text-gray-700 dark:text-gray-300"
                    >
                        <span
                            class="mdi mdi-phone text-gray-400 dark:text-gray-500 text-xl shrink-0 mt-0.5"
                        ></span>
                        <div class="flex items-center flex-wrap gap-1 text-sm">
                            <span class="text-gray-700 dark:text-gray-300">{{
                                address.phone
                            }}</span>
                            <span
                                v-if="address.secondary_phone"
                                class="text-gray-500 dark:text-gray-400"
                            >
                                • {{ address.secondary_phone }}
                            </span>
                        </div>
                    </div>

                    <!-- References -->
                    <div
                        v-if="address.references"
                        class="flex items-start space-x-2 text-gray-700 dark:text-gray-300"
                    >
                        <span
                            class="mdi mdi-note-text text-gray-400 dark:text-gray-500 text-xl shrink-0 mt-0.5"
                        ></span>
                        <span
                            class="text-sm flex-1 text-gray-500 dark:text-gray-400 italic"
                        >
                            Reference: {{ address.references }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div
            v-else
            class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-700"
        >
            <span
                class="mdi mdi-map-marker-outline text-gray-400 dark:text-gray-600 text-5xl"
            ></span>
            <h3
                class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100"
            >
                No addresses found
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                You haven't added any delivery addresses yet.
            </p>
            <button
                class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800"
            >
                <span class="mdi mdi-plus mr-2"></span>
                Add New Address
            </button>
        </div>

        <!-- Pagination -->
        <v-paginate
            :total-pages="pages.total_pages"
            v-model="search.page"
            @change="getDeliveryAddresses"
        />
    </v-account-layout>
</template>

<script setup>
import VAccountLayout from "@/components/VAccountLayout.vue";
import { usePage } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import VCreate from "./VCreate.vue";
import VDelete from "./VDelete.vue";
import VPaginate from "@/components/VPaginate.vue";

const page = usePage();

const addresses = ref([]);
const pages = ref({
    total_pages: 0,
});

const search = ref({
    per_page: 15,
    page: 1,
});

const getDeliveryAddresses = async () => {
    try {
        const res = await $server.get(page.props.api.address);
        if (res.status == 200) {
            const values = res.data;
            addresses.value = values.data;
            pages.value = values.meta.pagination;
        }
    } catch (error) {
        if (error?.response?.data?.message) {
            $notify.error(error.response.data.message);
        }
    }
};

onMounted(async () => {
    await getDeliveryAddresses();
});
</script>
