<template>
    <v-layout>
        <template #aside>
            <v-item-menu
                :items="page.props.menus"
                icon="mdi mdi-apps text-2xl"
                :collapse="true"
            />
        </template>
        <template #main>
            <v-head
                :title="__('Subscription Plans')"
                :description="
                    __('Create, edit, and manage subscription offerings')
                "
            >
                <template #actions>
                    <v-button
                        :label="__('Create plan')"
                        :to="page.props.routes.create"
                        variant="secondary"
                        as="a"
                    />
                </template>
                <template #bottom>
                    <div
                        class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4"
                    >
                        <v-input
                            :label="__('Name')"
                            v-model="search.name"
                            :placeholder="__('Plan name')"
                        />

                        <v-select
                            :label="__('Status')"
                            v-model="search.active"
                            :options="[
                                { name: __('All'), id: '' },
                                { name: __('Active'), id: '1' },
                                { name: __('Inactive'), id: '0' },
                            ]"
                        />

                        <v-select
                            :label="__('Bonus')"
                            v-model="search.bonus_activated"
                            @change="searcher"
                            :options="[
                                { name: __('All'), id: '' },
                                { name: __('Yes'), id: '1' },
                                { name: __('No'), id: '0' },
                            ]"
                        />

                        <v-input
                            :label="__('Bonus Days')"
                            v-model="search.bonus_duration"
                            :placeholder="__('Days')"
                            type="number"
                        />

                        <v-select
                            :label="__('Pagination')"
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
                                variant="secondary"
                            />

                            <v-button
                                @click="clearFilters"
                                :label="__('Clear')"
                                variant="secondary"
                            />
                        </div>
                    </div>
                </template>
            </v-head>

            <div class="mb-6">
                <v-table
                    :items="plans"
                    :loading="loading"
                    :per-page="search.per_page"
                    :show-pagination="false"
                    :empty-text="
                        __('No plans found. Try adjusting your filters.')
                    "
                    empty-icon="mdi-view-dashboard-off-outline"
                    loading-text="Loading subscription plans..."
                    table-class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
                    thead-class="bg-gray-50 dark:bg-gray-800"
                    tbody-class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800"
                >
                    <template #head>
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase"
                            >
                                {{ __("Plan Name") }}
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase"
                            >
                                {{ __("Price") }}
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase"
                            >
                                {{ __("Billing Period") }}
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase"
                            >
                                {{ __("Status") }}
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase"
                            >
                                {{ __("Actions") }}
                            </th>
                        </tr>
                    </template>

                    <template #default="{ items }">
                        <template v-for="(item, index) in items" :key="item.id">
                            <!-- Main row -->
                            <tr
                                class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-150 group"
                            >
                                <!-- Plan Name -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="shrink-0">
                                            <div
                                                class="w-10 h-10 bg-linear-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center"
                                            >
                                                <i
                                                    class="mdi mdi-view-dashboard text-white text-lg"
                                                ></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div
                                                class="text-sm font-semibold text-gray-900 dark:text-white"
                                            >
                                                {{ item.name }}
                                            </div>
                                            <div
                                                class="text-xs text-gray-500 dark:text-gray-400 font-mono"
                                            >
                                                {{ item.slug }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Price -->
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <div
                                            class="text-sm font-bold text-gray-900 dark:text-white"
                                        >
                                            {{ formatPrice(item.prices?.[0]) }}
                                        </div>
                                        <div
                                            class="text-xs text-gray-500 dark:text-gray-400"
                                        >
                                            {{ __("per") }}
                                            {{
                                                formatBillingPeriod(
                                                    item.prices?.[0]
                                                        ?.billing_period,
                                                )
                                            }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Billing Period -->
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300"
                                    >
                                        <i
                                            class="mdi mdi-calendar-clock text-xs"
                                        ></i>
                                        {{
                                            formatBillingPeriod(
                                                item.prices?.[0]
                                                    ?.billing_period,
                                            )
                                        }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4">
                                    <span
                                        :class="
                                            getStatusBadgeClass(item.active)
                                        "
                                        class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full font-medium"
                                    >
                                        <i
                                            :class="
                                                item.active
                                                    ? 'mdi mdi-check-circle'
                                                    : 'mdi mdi-circle-outline'
                                            "
                                            class="text-xs"
                                        ></i>
                                        {{
                                            item.active
                                                ? __("Active")
                                                : __("Inactive")
                                        }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                >
                                    <div
                                        class="flex items-center justify-end gap-2"
                                    >
                                        <v-button
                                            :title="__('Show Detail')"
                                            :to="item.links.show"
                                            variant="success"
                                            as="a"
                                            round
                                            icon="mdi mdi-eye"
                                        />

                                        <v-delete
                                            :item="item"
                                            @deleted="getPlans"
                                        />
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </template>
                </v-table>
            </div>

            <div class="px-3 pb-4">
                <v-paginate
                    v-if="pages.total_pages > 1"
                    :total-pages="pages.total_pages"
                    v-model="search.page"
                    @change="getPlans"
                />
            </div>
        </template>
    </v-layout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import VLayout from "@/components/VLayout.vue";
import VItemMenu from "@/components/VItemMenu.vue";
import VHead from "@/components/VHead.vue";
import VTable from "@/components/VTable.vue";
import VButton from "@/components/VButton.vue";
import VInput from "@/components/VInput.vue";
import VSelect from "@/components/VSelect.vue";
import VDelete from "./Delete.vue";
import VPaginate from "@/components/VPaginate.vue";
import { useForm, usePage } from "@inertiajs/vue3";

const page = usePage();

const plans = ref([]);
const loading = ref(false);
const search = useForm({
    page: 1,
    per_page: 15,
    name: "",
    active: "",
    bonus_activated: "",
    bonus_duration: "",
});
const pages = ref({ total_pages: 0 });

onMounted(() => {
    data(page.props.data);
});

const data = (data) => {
    const values = data;
    plans.value = values.data;
    pages.value = values.meta.pagination;
};

const getPlans = () => {
    loading.value = true;

    search.get(page.props.routes.plans, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (page) => {
            data(page.props.data);
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};

const searcher = () => {
    search.page = 1;
    getPlans();
};

const clearFilters = () => {
    search.page = 1;
    search.per_page = 15;
    search.name = "";
    search.active = "";
    search.bonus_activated = "";
    search.bonus_duration = "";
    getPlans();
};

const formatPrice = (price) => {
    if (!price) return "—";
    return `${price.amount_format} ${price.currency}`;
};

const formatBillingPeriod = (period) => {
    const map = {
        monthly: __("Monthly"),
        yearly: __("Yearly"),
        weekly: __("Weekly"),
        daily: __("Daily"),
    };
    return map[period] || period || "—";
};

const getStatusBadgeClass = (active) => {
    return active
        ? "bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400"
        : "bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-400";
};
</script>
