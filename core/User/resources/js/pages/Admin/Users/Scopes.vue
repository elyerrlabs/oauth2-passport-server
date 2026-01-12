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
        class="relative group w-12 h-12 border border-blue-600 dark:border-blue-400 text-blue-600 dark:text-blue-400 rounded-full hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 focus:ring-offset-2"
        :title="__('Manage Access Scopes')"
    >
        <i class="mdi mdi-shield-account-outline text-lg"></i>
        <div
            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50"
        >
            {{ __("Manage Scopes") }}
            <div
                class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"
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
                class="bg-blue-600 p-4 dark:bg-blue-700 text-white rounded-t-lg transition-colors duration-200"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-md">
                            {{ __("Manage permissions for:") }}
                            <span class="font-bold"
                                >{{ item.name }} {{ item.last_name }}</span
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Summary -->
            <div class="mt-6">
                <!-- Loading State -->
                <div
                    v-if="loading"
                    class="flex justify-center items-center py-16"
                >
                    <div class="text-center space-y-4">
                        <div class="relative">
                            <svg
                                class="animate-spin h-12 w-12 text-blue-600 dark:text-blue-400 mx-auto"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                            <div
                                class="absolute inset-0 flex items-center justify-center"
                            >
                                <i
                                    class="mdi mdi-shield-account text-blue-500"
                                ></i>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div
                                class="text-blue-600 dark:text-blue-400 font-semibold"
                            >
                                {{ __("Loading Permissions") }}
                            </div>
                            <div
                                class="text-gray-500 dark:text-gray-400 text-sm"
                            >
                                {{ __("Setting up your access controls...") }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Scopes Content -->
                <!-- Scopes Content -->
                <div v-else class="space-y-6">
                    <!-- Skeleton Loading -->
                    <div
                        v-if="showSkeleton && scopes.length === 0"
                        class="space-y-4"
                    >
                        <div
                            v-for="n in 3"
                            :key="n"
                            class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 space-y-4 bg-white dark:bg-gray-800"
                        >
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-1/4 animate-pulse"
                                    ></div>
                                    <div
                                        class="h-6 bg-gray-200 dark:bg-gray-700 rounded-full w-20 animate-pulse"
                                    ></div>
                                </div>
                                <div
                                    class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2 animate-pulse"
                                ></div>
                            </div>
                            <div class="space-y-4 ml-2">
                                <div
                                    v-for="m in 2"
                                    :key="m"
                                    class="border border-gray-200 dark:border-gray-700 rounded p-3"
                                >
                                    <div
                                        class="flex items-center justify-between mb-3"
                                    >
                                        <div
                                            class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-1/4 animate-pulse"
                                        ></div>
                                        <div
                                            class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/6 animate-pulse"
                                        ></div>
                                    </div>
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-2"
                                    >
                                        <div
                                            v-for="k in 3"
                                            :key="k"
                                            class="flex items-center space-x-2 p-2"
                                        >
                                            <div
                                                class="w-5 h-5 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"
                                            ></div>
                                            <div class="flex-1 space-y-1">
                                                <div
                                                    class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"
                                                ></div>
                                                <div
                                                    class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-3/4 animate-pulse"
                                                ></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actual Scopes Content -->
                    <div v-else class="space-y-6 overflow-y-auto pr-2">
                        <!-- Empty State -->
                        <div
                            v-if="scopes.length === 0"
                            class="text-center py-16"
                        >
                            <div
                                class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6"
                            >
                                <i
                                    class="mdi mdi-shield-off text-gray-400 text-3xl"
                                ></i>
                            </div>
                            <div class="space-y-3">
                                <div
                                    class="text-gray-600 dark:text-gray-300 text-xl font-semibold"
                                >
                                    {{ __("No Permissions Available") }}
                                </div>
                                <div
                                    class="text-gray-500 dark:text-gray-400 text-sm max-w-md mx-auto"
                                >
                                    {{
                                        __(
                                            "Permissions haven't been configured yet. Contact your system administrator to set up access controls."
                                        )
                                    }}
                                </div>
                            </div>
                        </div>

                        <!-- Grouped Scopes -->
                        <div
                            v-else
                            v-for="(group, index) in groupedScopes"
                            :key="index"
                            class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 space-y-4 bg-white dark:bg-gray-800"
                        >
                            <!-- Group Header -->
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div
                                        class="flex items-center space-x-3 mb-2"
                                    >
                                        <div
                                            class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center"
                                        >
                                            <i
                                                class="mdi mdi-folder-account text-white"
                                            ></i>
                                        </div>
                                        <div>
                                            <div
                                                class="font-semibold text-gray-900 dark:text-white"
                                            >
                                                {{ __(group.name) }}
                                            </div>
                                            <div
                                                class="text-sm text-gray-600 dark:text-gray-400 mt-1"
                                            >
                                                {{ __(group.description) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full"
                                >
                                    {{ group.services.length }}
                                    {{ __("services") }}
                                </div>
                            </div>

                            <!-- Services -->
                            <div class="space-y-4">
                                <div
                                    v-for="(service, index) in group.services"
                                    :key="index"
                                    class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-white dark:bg-gray-900/30"
                                >
                                    <!-- Service Header -->
                                    <div
                                        class="flex items-center justify-between mb-4"
                                    >
                                        <div
                                            class="flex items-center space-x-3"
                                        >
                                            <div
                                                class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center"
                                            >
                                                <i
                                                    class="mdi mdi-cog text-blue-600 dark:text-blue-400"
                                                ></i>
                                            </div>
                                            <div>
                                                <span
                                                    class="font-medium text-gray-800 dark:text-white"
                                                >
                                                    {{ __(service.name) }}
                                                </span>
                                                <div
                                                    v-if="service.description"
                                                    class="text-gray-600 dark:text-gray-400 text-sm mt-1"
                                                >
                                                    {{
                                                        __(service.description)
                                                    }}
                                                </div>
                                            </div>
                                        </div>
                                        <span
                                            class="text-gray-500 dark:text-gray-400 text-sm bg-white dark:bg-gray-800 px-2 py-1 rounded-full border"
                                        >
                                            {{ service.roles.length }}
                                            {{ __("roles") }}
                                        </span>
                                    </div>

                                    <!-- Roles -->
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2"
                                    >
                                        <label
                                            v-for="role in service.roles"
                                            :key="role.id"
                                            class="flex items-start space-x-2 p-3 rounded-lg border transition-all duration-150 cursor-pointer"
                                            :class="{
                                                'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800':
                                                    findScope(role?.scope?.id),
                                                'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800':
                                                    !findScope(role?.scope?.id),
                                            }"
                                        >
                                            <div
                                                class="flex-1 min-w-0 space-y-1"
                                            >
                                                <div
                                                    class="flex items-center justify-between"
                                                >
                                                    <div
                                                        class="text-sm font-medium text-gray-900 dark:text-white truncate"
                                                    >
                                                        {{ __(role.name) }}
                                                    </div>
                                                    <i
                                                        v-if="
                                                            findScope(
                                                                role?.scope?.id
                                                            )
                                                        "
                                                        class="mdi mdi-check-circle text-blue-600 dark:text-blue-400"
                                                    ></i>
                                                </div>
                                                <div
                                                    class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2"
                                                >
                                                    {{ __(role.description) }}
                                                </div>
                                                <div
                                                    class="flex items-center justify-end"
                                                >
                                                    <v-add-scope
                                                        :item="role"
                                                        @created="userRoles"
                                                        v-if="
                                                            !findScope(
                                                                role?.scope?.id
                                                            )
                                                        "
                                                    />
                                                    <v-delete-scope
                                                        @deleted="userRoles"
                                                        :item="
                                                            findScope(
                                                                role?.scope?.id
                                                            )
                                                        "
                                                        v-else
                                                    />
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </v-modal>
</template>
<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import VModal from "@/components/VModal.vue";
import VDeleteScope from "./VDeleteScope.vue";
import VAddScope from "./VAddScope.vue";

/* ================= PROPS / EMITS ================= */

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
});

const page = usePage();

/* ================= STATE ================= */

const dialog = ref(false);
const loading = ref(false);
const showSkeleton = ref(true);

const scopes = ref([]);
const user_scopes = ref([]);

/* ================= ACTIONS ================= */

const openDialog = async () => {
    dialog.value = true;
    loading.value = true;
    await userRoles();
    await getScopes();
    loading.value = false;
};

/* ================= DATA ================= */

const userRoles = async () => {
    try {
        const res = await $server.get(props.item.links.scopes);
        if (res.status === 200) {
            user_scopes.value = res.data.data;
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    }
};

const getScopes = async () => {
    try {
        const res = await $server.get(page.props.scopes, {
            params: { per_page: 200 },
        });

        if (res.status === 200) {
            scopes.value = res.data.data;

            setTimeout(() => {
                showSkeleton.value = false;
                loading.value = false;
            }, 200);
        }
    } catch (e) {
        showSkeleton.value = false;
        loading.value = false;
    }
};

const findScope = (scopeId) => {
    if (!user_scopes.value.length) {
        return;
    }

    return user_scopes.value.find((item) => item.scope.id == scopeId);
};

/* ================= HELPERS ================= */

const groupedScopes = computed(() => {
    const grouped = {};

    scopes.value.forEach((item) => {
        const { service, role } = item;
        const group = service.group;

        const groupName = group.name;
        const serviceName = service.name;

        /* ================= SCOPE NORMALIZED ================= */

        const scopeData = {
            id: item.id,
            gsr_id: item.gsr_id,
            public: item.public,
            active: item.active,
            web: item.web,
            api_key: item.api_key,
            links: {
                scopes: props.item.links.scopes, // user route to add scopes
            },
        };

        /* ================= ROLE NORMALIZED ================= */

        const roleData = {
            id: role.id,
            name: role.name,
            slug: role.slug,
            description: role.description,
            scope: scopeData,
        };

        /* ================= GROUP ================= */

        if (!grouped[groupName]) {
            grouped[groupName] = {
                name: groupName,
                description: group.description,
                services: {},
            };
        }

        /* ================= SERVICE ================= */

        if (!grouped[groupName].services[serviceName]) {
            grouped[groupName].services[serviceName] = {
                name: service.name,
                description: service.description,
                roles: [],
            };
        }

        /* ================= PUSH ROLE ================= */

        grouped[groupName].services[serviceName].roles.push(roleData);
    });

    return Object.values(grouped);
});
</script>
