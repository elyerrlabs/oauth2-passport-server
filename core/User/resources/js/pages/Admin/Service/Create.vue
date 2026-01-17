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
    <button
        v-if="item?.id"
        @click="open"
        class="relative group w-12 h-12 gap-2 border border-blue-600 dark:border-blue-400 px-4 py-2 text-blue-600 dark:text-blue-400 rounded-full hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800"
    >
        <i class="mdi mdi-pencil text-lg"></i>

        <!-- Tooltip -->
        <div
            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-blue-600 dark:bg-blue-500 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50"
        >
            {{ __("Edit Service") }}
            <div
                class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-blue-600 dark:border-t-blue-500"
            ></div>
        </div>
    </button>

    <!-- Create Button -->
    <button
        v-else
        @click="dialog = true"
        class="relative group rounded-lg bg-blue-600 dark:bg-blue-700 text-white px-4 py-3 shadow-lg hover:shadow-xl hover:bg-blue-700 dark:hover:bg-blue-600 transition-all duration-300 hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 flex items-center gap-2"
    >
        <i class="mdi mdi-plus-circle text-md lg:text-xl"></i>
        {{ __("New Service") }}
    </button>

    <v-modal
        v-model="dialog"
        :title="__('Create New Service')"
        panel-class="w-full lg:w-4xl"
    >
        <template #body>
            <!-- Form Content -->
            <div class="p-6 space-y-6">
                <!-- Name and System Service -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <v-input
                        v-model="form.name"
                        :error="form.errors.name"
                        :required="true"
                        :label="__('Service Name')"
                        :placeholder="__('Enter service name')"
                        :disabled="item?.system"
                    />

                    <div class="flex items-end">
                        <v-switch
                            v-show="item?.system"
                            v-model="form.system"
                            :error="form.errors.system"
                            :label="__('System Service')"
                            :disabled="item?.system"
                            :placeholder="
                                __(
                                    'System services have special permissions and cannot be deleted'
                                )
                            "
                        />
                    </div>
                </div>

                <!-- Description -->
                <v-textarea
                    v-model="form.description"
                    :error="form.errors.description"
                    :label="__('Description')"
                    :required="true"
                    :placeholder="__('Enter service description...')"
                    :disabled="item?.system"
                />

                <!-- Group and Visibility -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <v-select
                        v-model="form.group_id"
                        :options="groups"
                        :placeholder="__('Select group')"
                        :required="true"
                        :label="__('Group')"
                        :error="form.errors.group_id"
                        v-if="!item?.id"
                        :disabled="item?.system"
                    />

                    <v-select
                        v-model="form.visibility"
                        :label="__('Visibility')"
                        :placeholder="__('Select visibility level')"
                        :options="visibilityOptions"
                        :required="true"
                        :error="form.errors.visibility"
                        :disabled="item?.system"
                    />
                </div>
            </div>

            <!-- Actions -->
            <div
                class="flex justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 transition-colors duration-200"
            >
                <button
                    @click="close"
                    :disabled="loading"
                    class="px-6 py-2 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-600 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ __("Cancel") }}
                </button>
                <button
                    @click="createOrUpdate"
                    :disabled="loading"
                    :class="[
                        'flex items-center gap-2 px-6 py-2 text-white rounded-lg focus:outline-none focus:ring-2 transition-colors duration-200',
                        loading
                            ? 'bg-blue-400 dark:bg-blue-600 cursor-not-allowed'
                            : 'bg-blue-600 dark:bg-blue-700 hover:bg-blue-700 dark:hover:bg-blue-600 focus:ring-blue-200 dark:focus:ring-blue-800',
                    ]"
                >
                    <i v-if="loading" class="mdi mdi-loading animate-spin"></i>
                    <i
                        v-else
                        :class="
                            item?.id
                                ? 'mdi mdi-content-save'
                                : 'mdi mdi-check-circle'
                        "
                    ></i>
                    <span>
                        {{
                            loading
                                ? item?.id
                                    ? __("Saving...")
                                    : __("Creating...")
                                : item?.id
                                ? __("Update Service")
                                : __("Create Service")
                        }}
                    </span>
                </button>
            </div>
        </template>
    </v-modal>
</template>

<script setup>
import { ref, onMounted } from "vue";
import VModal from "@/components/VModal.vue";
import VInput from "@/components/VInput.vue";
import VTextarea from "@/components/VTextarea.vue";
import VSwitch from "@/components/VSwitch.vue";
import VSelect from "@/components/VSelect.vue";
import { useForm, usePage } from "@inertiajs/vue3";

const page = usePage();

const emits = defineEmits(["created", "updated"]);

const props = defineProps({
    item: {
        type: Object,
        default: [],
    },
});

const dialog = ref(false);
const loading = ref(false);

const visibilityOptions = [
    { name: __("Private"), id: "private" },
    { name: __("Public"), id: "public" },
];

const form = useForm({
    name: null,
    description: null,
    group_id: null,
    system: false,
    visibility: null,
});

const groups = ref([]);

function close() {
    form.reset();
    dialog.value = false;
    loading.value = false;
}

onMounted(() => {
    groups.value = page.props.groups.data;
});

const open = () => {
    form.resetAndClearErrors();

    const values = props.item;

    if (values) {
        form.name = values.name;
        form.description = values.description;
        form.group_id = values?.group?.id;
        form.system = values.system;
        form.visibility = values.visibility;
    }

    dialog.value = true;
};

const createOrUpdate = () => {
    loading.value = true;

    if (props.item?.id) {
        form.put(props.item?.links?.update, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                $notify.success(__("Service updated successfully"));
                form.reset();
                emits("updated");
                dialog.value = false;
            },
            onFinish: () => {
                loading.value = false;
            },
        });
    } else {
        form.post(page.props.route, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                $notify.success(__("Service created successfully"));
                form.reset();
                emits("created");
                dialog.value = false;
            },
            onFinish: () => {
                loading.value = false;
            },
        });
    }
};
</script>

<style scoped>
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>
