<!--
Copyright (c) 2025 Elvis Yerel Roman Concha

This file is part of an open source project licensed under the
"NON-COMMERCIAL USE LICENSE - OPEN SOURCE PROJECT" (Effective Date: 2025-08-03).

You may use, study, modify, and redistribute this file for personal,
educational, or non-commercial research purposes only.

Commercial use is strictly prohibited without prior written consent
from the author.

Combining this software with any project licensed for commercial use
(such as AGPL) is not permitted without explicit authorization.

This software supports OAuth 2.0 and OpenID Connect.

Author Contact: yerel9212@yahoo.es

SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project
-->
<template>
    <!-- Action Button -->
    <button
        v-if="item?.id"
        @click="open"
        class="w-8 h-8 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-full flex items-center justify-center transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
    >
        <i class="mdi mdi-pencil text-lg"></i>
    </button>

    <button
        v-else
        @click="dialog = true"
        class="rounded flex justify-between items-center text-md bg-blue-600 dark:bg-blue-700 text-white p-2 hover:shadow-xl hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800"
    >
        <i class="mdi mdi-plus-circle text-3xl"></i>
        <span>
            {{ __("New role") }}
        </span>
    </button>

    <!-- Modal -->
    <v-modal
        v-model="dialog"
        :title="!item?.id ? __('Create New Role') : __('Update Role')"
        panel-class="w-full lg:w-4xl"
    >
        <template #body>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <v-input
                    :label="__('Name')"
                    v-model="form.name"
                    :placeholder="__('Enter role name')"
                    :required="true"
                    :error="form.errors.name"
                    :disabled="item?.system"
                />

                <div class="flex items-end">
                    <v-switch
                         v-show="item?.id | item?.system"
                        :label="__('System Role')"
                        v-model="form.system"
                        :error="form.errors.system"
                        :placeholder="
                            __(
                                'System roles have special permissions and cannot be deleted.'
                            )
                        "
                        :disabled="item?.id"
                    />
                </div>
            </div>

            <div class="px-6 pb-6 grid grid-cols-1">
                <v-textarea
                    :label="__('Description')"
                    v-model="form.description"
                    :placeholder="__('Enter role description...')"
                    :required="true"
                    :error="form.errors.description"
                    :disabled="item?.system"
                />
            </div>

            <!-- Actions -->
            <div
                class="flex justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-b-2xl"
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
                                ? __("Update Role")
                                : __("Create Role")
                        }}
                    </span>
                </button>
            </div>
        </template>
    </v-modal>
</template>

<script setup>
import VModal from "@/components/VModal.vue";
import VInput from "@/components/VInput.vue";
import VTextarea from "@/components/VTextarea.vue";
import VSwitch from "@/components/VSwitch.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

const page = usePage();

const emits = defineEmits(["created", "updated"]);

const props = defineProps({
    item: {
        type: Object,
        default: null,
    },
});

const dialog = ref(false);
const loading = ref(false);
const form = useForm({
    name: null,
    description: null,
    system: false,
});

// methods
const open = () => {
    dialog.value = true;
    form.reset();
    if (props.item?.id) {
        form.name = props.item.name;
        form.description = props.item.description;
        form.system = props.item.system;
    }
};

const close = () => {
    form.reset();
    dialog.value = false;
    loading.value = false;
};

const createOrUpdate = () => {
    loading.value = true;

    if (props.item?.id) {
        form.put(props.item.links.update, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                $notify.success(__("Role updated successfully"));
                dialog.value = false;
                form.reset();
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
                $notify.success(__("Role created successfully"));
                dialog.value = false;
                form.reset();
            },
            onFinish: () => {
                loading.value = false;
            },
        });
    }
};
</script>
