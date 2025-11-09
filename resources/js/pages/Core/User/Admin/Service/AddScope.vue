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
    <!-- Button -->
    <button
        v-if="!scope || scope?.id"
        @click="open"
        class="relative group w-12 h-12 gap-2 border border-blue-600 dark:border-blue-400 px-4 py-2 text-blue-600 dark:text-blue-400 rounded-full hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800"
    >
        <i :class="icon"></i>

        <!-- Tooltip -->
        <div
            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-blue-600 dark:bg-blue-500 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50"
        >
            {{ scope ? __("Update Scope") : __("Add Scope") }}
            <div
                class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-blue-600 dark:border-t-blue-500"
            ></div>
        </div>
    </button>

    <v-modal
        v-model="dialog"
        :title="scope ? __('Update Scope') : __('Add New Scope')"
        panel-class="w-full lg:w-6xl"
    >
        <template #body>
            <!-- Form Content -->
            <div class="p-6 space-y-6">
                <!-- Role Select -->
                <div class="space-y-2">
                    <label
                        class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        <i
                            class="mdi mdi-account-key text-gray-500 dark:text-gray-400"
                        ></i>
                        {{ __("Role") }}
                    </label>
                    <div class="relative">
                        <v-select
                            :label="__('Select Role')"
                            v-model="form.role_id"
                            :error="form.errors.role_id"
                            :options="roles"
                            :required="true"
                        />
                    </div>
                </div>
                <!-- Permissions Section -->
                <div class="space-y-4">
                    <h4
                        class="text-lg font-medium text-gray-800 dark:text-gray-200"
                    >
                        {{ __("Permissions") }}
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- API Key Access -->
                        <v-switch
                            v-model="form.api_key"
                            :label="__('API Key Access')"
                            :error="form.errors.api_key"
                            :required="true"
                            :placeholder="
                                __(
                                    'Allow access via API keys for automated systems'
                                )
                            "
                        />

                        <!-- Web Access -->
                        <v-switch
                            v-model="form.web"
                            :label="__('Web Access')"
                            :error="form.errors.web"
                            :required="true"
                            :placeholder="
                                __(
                                    'Make available for web routes (requires login)'
                                )
                            "
                        />

                        <!-- Active -->
                        <v-switch
                            v-model="form.active"
                            :label="__('Active')"
                            :error="form.errors.active"
                            :required="true"
                            :placeholder="
                                __('Enable this scope for immediate use')
                            "
                        />

                        <!-- Public Access -->
                        <v-switch
                            v-model="form.public"
                            :label="__('Public Access')"
                            :error="form.errors.public"
                            :required="true"
                            :placeholder="
                                __(
                                    'Make available to all users without authentication'
                                )
                            "
                        />
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div
                class="flex justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-b-2xl transition-colors duration-200"
            >
                <button
                    @click="dialog = false"
                    :disabled="loading"
                    class="px-6 py-2 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-600 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ __("Cancel") }}
                </button>
                <button
                    @click="addScopes"
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
                        :class="scope ? 'mdi mdi-update' : 'mdi mdi-plus'"
                    ></i>
                    <span>{{
                        loading
                            ? __(scope ? "Updating..." : "Adding...")
                            : scope
                            ? __("Update Scope")
                            : __("Add Scope")
                    }}</span>
                </button>
            </div>
        </template>
    </v-modal>
</template>

<script setup>
import { ref } from "vue";
import VModal from "@/components/VModal.vue";
import VSelect from "@/components/VSelect.vue";
import VSwitch from "@/components/VSwitch.vue";
import { useForm, usePage } from "@inertiajs/vue3";

const emits = defineEmits(["created"]);
const page = usePage();

const props = defineProps({
    icon: {
        type: String,
        default: "mdi mdi-lock-open-plus-outline",
    },
    scope: {
        type: Object,
        required: false,
    },
    link: {
        type: String,
        required: true,
    },
});

const dialog = ref(false);
const loading = ref(false);
const roles = ref([]);

const form = useForm({
    api_key: false,
    web: false,
    active: false,
    public: false,
    role_id: "",
});

const open = () => {
    roles.value = page.props.roles.data;

    if (props.scope?.id) {
        form.api_key = props.scope.api_key;
        form.web = props.scope.web;
        form.active = props.scope.active;
        form.public = props.scope.public;
        form.role_id = props.scope?.role?.id;
    }

    dialog.value = true;
};

const addScopes = () => {
    loading.value = true;

    form.post(props.link, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            $notify.success(
                props.scope?.id
                    ? __("Scope updated successfully")
                    : __("Scope added successfully")
            );
            emits("created");
            dialog.value = false;
        },
        onFinish: () => {
            loading.value = false;
        },
    });
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
