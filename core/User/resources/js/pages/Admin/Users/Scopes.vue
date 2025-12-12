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
    <!-- Manage Scopes Button -->
    <button
        @click="openDialog"
        class="relative group w-12 h-12 gap-2 border border-blue-600 dark:border-blue-400 px-4 py-2 text-blue-600 dark:text-blue-400 rounded-full hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800"
        :title="__('Manage Access Scopes')"
    >
        <i class="mdi mdi-shield-account-outline text-lg"></i>

        <!-- Tooltip -->
        <div
            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-blue-600 dark:bg-blue-500 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50"
        >
            {{ __("Manage Scopes") }}
            <div
                class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-blue-600 dark:border-t-blue-500"
            ></div>
        </div>
    </button>

    <v-modal
        v-model="dialog"
        :title="__('Manage Access Scopes')"
        panel-class="w-full lg:w-7xl"
    >
        <template #body>
            <!-- Header -->
            <div
                class="bg-blue-600 dark:bg-blue-700 text-white rounded-t-lg -mx-6 -mt-6 px-6 py-4 transition-colors duration-200"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-lg mt-1">
                            {{ __("Manage permissions for:") }}
                            <span class="font-bold"
                                >{{ item.name }} {{ item.last_name }}</span
                            >
                        </div>
                        <div
                            class="text-sm opacity-90 mt-1 text-blue-100 dark:text-blue-200"
                        >
                            {{ item.email }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="mt-6">
                <!-- User Summary -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg p-4 mb-6 border border-gray-200 dark:border-gray-700 transition-colors duration-200"
                >
                    <div class="flex items-center">
                        <div
                            class="w-12 h-12 bg-blue-600 dark:bg-blue-700 text-white rounded-full flex items-center justify-center text-lg font-bold mr-4 transition-colors duration-200"
                        >
                            {{
                                item.name
                                    ? item.name.charAt(0).toUpperCase()
                                    : "U"
                            }}
                        </div>
                        <div>
                            <div
                                class="text-lg font-semibold text-blue-600 dark:text-blue-400"
                            >
                                {{ item.name }} {{ item.last_name }}
                            </div>
                            <div
                                class="text-sm text-gray-600 dark:text-gray-400"
                            >
                                {{ item.email }}
                            </div>
                            <div class="mt-1">
                                <span
                                    :class="[
                                        'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium transition-colors duration-200',
                                        item.disabled
                                            ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-800 dark:text-orange-300'
                                            : 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300',
                                    ]"
                                >
                                    <i
                                        :class="[
                                            'mdi mr-1 text-xs',
                                            item.disabled
                                                ? 'mdi-close-circle text-orange-600 dark:text-orange-400'
                                                : 'mdi-check-circle text-green-600 dark:text-green-400',
                                        ]"
                                    ></i>
                                    {{
                                        item.disabled
                                            ? __("Inactive")
                                            : __("Active")
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Scopes Component -->
                <div class="scopes-container">
                    <v-scopes
                        :default_roles="user_roles"
                        @checked="checkedRoles"
                        :loading="loading"
                    />
                </div>
            </div>

            <!-- Footer Actions -->
            <div
                class="flex justify-end gap-3 mt-8 pt-4 border-t border-gray-200 dark:border-gray-700 transition-colors duration-200"
            >
                <button
                    @click="dialog = false"
                    class="flex items-center gap-2 px-6 py-2 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-600 transition-colors duration-200"
                >
                    <i class="mdi mdi-close-circle"></i>
                    {{ __("Cancel") }}
                </button>
                <button
                    @click="add"
                    :disabled="loading"
                    :class="[
                        'flex items-center gap-2 px-6 py-2 text-white rounded-lg focus:outline-none focus:ring-2 transition-colors duration-200',
                        loading
                            ? 'bg-blue-400 dark:bg-blue-600 cursor-not-allowed'
                            : 'bg-blue-600 dark:bg-blue-700 hover:bg-blue-700 dark:hover:bg-blue-600 focus:ring-blue-200 dark:focus:ring-blue-800',
                    ]"
                >
                    <i v-if="loading" class="mdi mdi-loading animate-spin"></i>
                    <i v-else class="mdi mdi-shield-check"></i>
                    <span>{{
                        loading ? __("Saving...") : __("Save Permissions")
                    }}</span>
                </button>
            </div>
        </template>
    </v-modal>
</template>

<script setup>
import VModal from "@/components/VModal.vue";
import VScopes from "@/components/VScopes.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    item: {
        required: true,
        type: Object,
    },
});

const user_roles = ref([]);
const dialog = ref(false);
const loading = ref(false);
const form = useForm({
    scopes: [],
    end_date: "",
});

const openDialog = async () => {
    dialog.value = true;
    loading.value = true;
    await userRoles();
    loading.value = false;
};

const userRoles = async () => {
    try {
        const res = await $server.get(props.item.links.scopes);

        if (res.status == 200) {
            user_roles.value = res.data.data;
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    }
};

const checkedRoles = (event) => {
    form.scopes = event;
};

const add = async () => {
    loading.value = true;

    form.post(props.item.links.scopes, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            $notify.success(__("Permissions updated successfully"));
            userRoles();
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
