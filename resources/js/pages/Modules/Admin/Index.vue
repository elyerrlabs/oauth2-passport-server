<template>
    <v-main-layout>
        <v-head :title="__('Modules Installed')">
            <template #actions>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ modules?.data?.length || 0 }} {{ __("modules") }}
                    </span>
                    <button
                        @click="refreshModules"
                        class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                    >
                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                            />
                        </svg>
                    </button>
                </div>
            </template>
            <template #bottom>
                <div class="grid grid-cols-4 gap-4">
                    <v-input :label="__('name')" v-model="search.name" />

                    <v-select
                        :label="__('Per page')"
                        v-model="search.per_page"
                        :options="[
                            { name: 20, id: 20 },
                            { name: 50, id: 50 },
                            { name: 100, id: 100 },
                            { name: 150, id: 150 },
                            { name: 300, id: 300 },
                        ]"
                    />

                    <div class="flex justify-between items-end">
                        <v-button
                            :label="__('Search')"
                            @click="searcher"
                            variant="primary"
                        />
                        <v-button
                            :label="__('Reset')"
                            @click="reset"
                            variant="danger"
                        />
                    </div>
                </div>
            </template>
        </v-head>

        <v-table
            :items="modules"
            :loading="loading"
            :per-page="search?.per_page || 20"
            :show-pagination="false"
            empty-icon="mdi mdi-file-document-outline"
            loading-text="Loading modules..."
            table-class="min-w-full"
            thead-class="bg-gray-50 dark:bg-gray-800/50"
            tbody-class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
        >
            <template #head>
                <tr>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                    >
                        {{ __("Module") }}
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                    >
                        {{ __("Provider") }}
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                    >
                        {{ __("Source") }}
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                    >
                        {{ __("Version") }}
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                    >
                        {{ __("Status") }}
                    </th>
                    <th
                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                    >
                        {{ __("Actions") }}
                    </th>
                </tr>
            </template>

            <template #default="{ items }">
                <tr
                    v-for="item in items"
                    :key="item.id"
                    class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
                >
                    <!-- Name -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-lg flex items-center justify-center text-white font-semibold text-sm"
                                :style="{
                                    backgroundColor: getColor(item.name),
                                }"
                            >
                                {{ item.name.charAt(0).toUpperCase() }}
                            </div>
                            <div>
                                <div
                                    class="text-sm font-medium text-gray-900 dark:text-gray-100"
                                >
                                    {{ item.name }}
                                </div>
                                <div
                                    class="text-xs text-gray-400 dark:text-gray-500 font-mono truncate max-w-45"
                                >
                                    {{ item.path }}
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Provider -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                            :class="{
                                'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300':
                                    item.provider === 'git',
                                'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200':
                                    item.provider === 'local',
                            }"
                        >
                            {{ item.provider }}
                        </span>
                    </td>

                    <!-- Source -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span
                            class="text-sm text-gray-600 dark:text-gray-400 font-mono truncate block max-w-50"
                            :title="item.source"
                        >
                            {{ item.source }}
                        </span>
                    </td>

                    <!-- Version -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300"
                            >
                                {{ item.current_version || "—" }}
                            </span>
                            <span
                                v-if="item.new_version"
                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200"
                            >
                                ↑ {{ item.new_version }}
                            </span>
                        </div>
                    </td>

                    <!-- Status -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium"
                            :class="
                                item.enabled
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                    : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                            "
                        >
                            <span
                                class="w-1.5 h-1.5 rounded-full"
                                :class="
                                    item.enabled ? 'bg-green-500' : 'bg-red-500'
                                "
                            ></span>
                            {{ item.enabled ? __("Active") : __("Inactive") }}
                        </span>
                    </td>

                    <!-- Actions -->
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex justify-end items-center gap-1">
                            <v-details
                                :module="item"
                                @disabled="getPages"
                                @enabled="getPages"
                            />
                        </div>
                    </td>
                </tr>

                <!-- Empty State -->
                <tr v-if="!items || items.length === 0">
                    <td colspan="6" class="px-6 py-12 text-center">
                        <svg
                            class="mx-auto h-12 w-12 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"
                            />
                        </svg>
                        <p
                            class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                        >
                            {{ __("No modules found") }}
                        </p>
                    </td>
                </tr>
            </template>
        </v-table>

        <v-paginate
            v-model="search.page"
            :total-pages="pages.total_pages"
            @change="getPages"
        />
    </v-main-layout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import VMainLayout from "@/components/VMainLayout.vue";
import VTable from "@/components/VTable.vue";
import VInput from "@/components/VInput.vue";
import VSelect from "@/components/VSelect.vue";
import VPaginate from "@/components/VPaginate.vue";
import VHead from "@/components/VHead.vue";
import VButton from "@/components/VButton.vue";
import { usePage, router, useForm } from "@inertiajs/vue3";
import VDetails from "./VDetails.vue";

const page = usePage();

const modules = ref({});
const loading = ref(false);
const pages = ref({
    total_pages: 0,
});
const search = useForm({
    page: 1,
    per_page: 20,
    name: "",
});

onMounted(() => {
    values(page.props.data);
});

const searcher = () => {
    search.page = 1;
    getPages();
};

const reset = () => {
    search.page = 1;
    search.per_page = 20;
    search.name = "";
    getPages();
};

const values = (data) => {
    modules.value = data.data;
    pages.value = data.meta.pagination;
};

const getPages = () => {
    search.get(page.props.routes.modules, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (page) => {
            values(page.props.data);
        },
    });
};

function getColor(name) {
    const colors = [
        "#6366f1",
        "#8b5cf6",
        "#3b82f6",
        "#06b6d4",
        "#10b981",
        "#f59e0b",
        "#ef4444",
        "#ec4899",
    ];
    const index = name
        .split("")
        .reduce((acc, char) => acc + char.charCodeAt(0), 0);
    return colors[index % colors.length];
}

function refreshModules() {
    loading.value = true;
    router.reload({
        onFinish: () => {
            loading.value = false;
        },
    });
}
</script>
