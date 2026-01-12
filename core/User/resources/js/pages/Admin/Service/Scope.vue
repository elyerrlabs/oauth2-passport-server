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
        @click="open"
        class="relative group w-12 h-12 gap-2 border border-blue-600 dark:border-blue-400 px-4 py-2 text-blue-600 dark:text-blue-400 rounded-full hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800"
    >
        <i class="mdi mdi-shield-lock-open-outline"></i>

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
        :title="__('Scope Management')"
        panel-class="w-full lg:w-7xl"
    >
        <template #body>
            <!-- Header -->
            <div class="text-gray-700 dark:text-gray-300 p-6">
                <div
                    class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4"
                >
                    <div class="flex-1">
                        <p class="text-gray-700 dark:text-gray-300 mt-1">
                            {{ __("Managing permissions for:") }}
                            <strong class="text-blue-600 dark:text-blue-400">{{
                                __(service.name)
                            }}</strong>
                        </p>
                        <div
                            class="flex items-center gap-1 text-gray-700 dark:text-gray-400 text-sm mt-1"
                        >
                            <i class="mdi mdi-account-group"></i>
                            {{ __("Group:") }}
                            {{ __(service.group?.name) || __("Not assigned") }}
                        </div>
                    </div>
                    <div>
                        <v-add-scope
                            :link="service.links.scopes"
                            @created="getScopes"
                        />
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 overflow-y-auto">
                <!-- Statistics -->
                <div
                    v-if="scopesCount > 0"
                    class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-8"
                >
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 text-center transition-colors duration-200"
                    >
                        <div
                            class="text-2xl font-bold text-blue-700 dark:text-blue-300"
                        >
                            {{ scopesCount }}
                        </div>
                        <div class="text-sm text-blue-600 dark:text-blue-400">
                            {{ __("Total Scopes") }}
                        </div>
                        <i
                            class="mdi mdi-lock text-blue-500 dark:text-blue-400 text-xl mt-2"
                        ></i>
                    </div>

                    <div
                        class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 text-center transition-colors duration-200"
                    >
                        <div
                            class="text-2xl font-bold text-green-700 dark:text-green-300"
                        >
                            {{ activeScopesCount }}
                        </div>
                        <div class="text-sm text-green-600 dark:text-green-400">
                            {{ __("Active") }}
                        </div>
                        <i
                            class="mdi mdi-check-circle text-green-500 dark:text-green-400 text-xl mt-2"
                        ></i>
                    </div>

                    <div
                        class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4 text-center transition-colors duration-200"
                    >
                        <div
                            class="text-2xl font-bold text-orange-700 dark:text-orange-300"
                        >
                            {{ apiKeyScopesCount }}
                        </div>
                        <div
                            class="text-sm text-orange-600 dark:text-orange-400"
                        >
                            {{ __("API Key Access") }}
                        </div>
                        <i
                            class="mdi mdi-key text-orange-500 dark:text-orange-400 text-xl mt-2"
                        ></i>
                    </div>

                    <div
                        class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4 text-center transition-colors duration-200"
                    >
                        <div
                            class="text-2xl font-bold text-purple-700 dark:text-purple-300"
                        >
                            {{ webScopesCount }}
                        </div>
                        <div
                            class="text-sm text-purple-600 dark:text-purple-400"
                        >
                            {{ __("Web Access") }}
                        </div>
                        <i
                            class="mdi mdi-web text-purple-500 dark:text-purple-400 text-xl mt-2"
                        ></i>
                    </div>

                    <div
                        class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-4 text-center transition-colors duration-200"
                    >
                        <div
                            class="text-2xl font-bold text-indigo-700 dark:text-indigo-300"
                        >
                            {{ publicScopesCount }}
                        </div>
                        <div
                            class="text-sm text-indigo-600 dark:text-indigo-400"
                        >
                            {{ __("Public Access") }}
                        </div>
                        <i
                            class="mdi mdi-earth text-indigo-500 dark:text-indigo-400 text-xl mt-2"
                        ></i>
                    </div>
                </div>

                <!-- Scopes Grid -->
                <div
                    v-if="scopesCount > 0"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                >
                    <div
                        v-for="item in scopes"
                        :key="item.gsr_id"
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-md hover:shadow-lg dark:hover:shadow-gray-900 transition-all duration-200"
                    >
                        <!-- Card Header -->
                        <div
                            class="bg-white dark:bg-gray-800 p-4 rounded-t-lg border-b border-gray-200 dark:border-gray-700 transition-colors duration-200"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <div
                                        class="flex items-center gap-2 text-blue-600 dark:text-blue-400 font-bold text-lg"
                                    >
                                        <i class="mdi mdi-account-key"></i>
                                        <span class="truncate">{{
                                            item.role.name
                                        }}</span>
                                    </div>
                                </div>
                                <button
                                    @click="copyToClipboard(item.gsr_id)"
                                    class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors duration-200"
                                >
                                    <i class="mdi mdi-content-copy"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Scope Details -->
                        <div class="p-4 space-y-4">
                            <div>
                                <div
                                    class="text-sm text-gray-500 dark:text-gray-400 mb-1"
                                >
                                    {{ __("GSR_ID") }}
                                </div>
                                <div
                                    class="font-mono text-sm font-medium bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-gray-800 dark:text-gray-200"
                                >
                                    {{ item.gsr_id }}
                                </div>
                            </div>

                            <!-- Permissions -->
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <i
                                            :class="
                                                item.api_key
                                                    ? 'mdi mdi-key-check text-green-500 dark:text-green-400'
                                                    : 'mdi mdi-key-remove text-red-500 dark:text-red-400'
                                            "
                                        ></i>
                                        <span
                                            class="text-sm text-gray-700 dark:text-gray-300"
                                            >{{ __("API Key Access") }}</span
                                        >
                                    </div>
                                    <span
                                        :class="
                                            item.api_key
                                                ? 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300'
                                                : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'
                                        "
                                        class="px-2 py-1 rounded-full text-xs transition-colors duration-200"
                                    >
                                        {{
                                            item.api_key
                                                ? __("Enabled")
                                                : __("Disabled")
                                        }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <i
                                            :class="
                                                item.web
                                                    ? 'mdi mdi-web text-blue-500 dark:text-blue-400'
                                                    : 'mdi mdi-web-off text-gray-400 dark:text-gray-500'
                                            "
                                        ></i>
                                        <span
                                            class="text-sm text-gray-700 dark:text-gray-300"
                                        >
                                            {{ __("Web Access") }}
                                        </span>
                                    </div>
                                    <span
                                        :class="
                                            item.web
                                                ? 'bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300'
                                                : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'
                                        "
                                        class="px-2 py-1 rounded-full text-xs transition-colors duration-200"
                                    >
                                        {{
                                            item.web
                                                ? __("Enabled")
                                                : __("Disabled")
                                        }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <i
                                            :class="
                                                item.active
                                                    ? 'mdi mdi-check-circle text-green-500 dark:text-green-400'
                                                    : 'mdi mdi-close-circle text-red-500 dark:text-red-400'
                                            "
                                        ></i>
                                        <span
                                            class="text-sm text-gray-700 dark:text-gray-300"
                                            >{{ __("Active Status") }}</span
                                        >
                                    </div>
                                    <span
                                        :class="
                                            item.active
                                                ? 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300'
                                                : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'
                                        "
                                        class="px-2 py-1 rounded-full text-xs transition-colors duration-200"
                                    >
                                        {{
                                            item.active
                                                ? __("Active")
                                                : __("Inactive")
                                        }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <i
                                            :class="
                                                item.public
                                                    ? 'mdi mdi-earth text-blue-500 dark:text-blue-400'
                                                    : 'mdi mdi-earth-off text-gray-500 dark:text-gray-400'
                                            "
                                        ></i>
                                        <span
                                            class="text-sm text-gray-700 dark:text-gray-300"
                                            >{{ __("Public Access") }}</span
                                        >
                                    </div>
                                    <span
                                        :class="
                                            item.public
                                                ? 'bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300'
                                                : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'
                                        "
                                        class="px-2 py-1 rounded-full text-xs transition-colors duration-200"
                                    >
                                        {{
                                            item.public
                                                ? __("Public")
                                                : __("Private")
                                        }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div
                            class="flex justify-end gap-2 p-4 border-t border-gray-200 dark:border-gray-700 transition-colors duration-200"
                        >
                            <v-delete-scope
                                :scope="item"
                                @deleted="getScopes"
                            />
                            <v-add-scope
                                icon="mdi mdi-pencil-lock-outline"
                                :scope="item"
                                :link="service.links.scopes"
                                @created="getScopes"
                            />
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="scopesCount === 0" class="text-center py-16">
                    <i
                        class="mdi mdi-lock-off-outline text-6xl text-gray-300 dark:text-gray-600"
                    ></i>
                    <div class="text-xl text-gray-500 dark:text-gray-400 mt-4">
                        {{ __("No scopes configured") }}
                    </div>
                    <div class="text-gray-400 dark:text-gray-500 mb-6">
                        {{
                            __(
                                "Add access permissions to control who can use this service"
                            )
                        }}
                    </div>
                    <v-add-scope
                        :link="service.links.scopes"
                        @created="getScopes"
                    />
                </div>
            </div>

            <!-- Footer -->
            <div
                class="flex justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 transition-colors duration-200"
            >
                <button
                    @click="dialog = false"
                    class="flex items-center gap-2 px-6 py-2 text-blue-600 dark:text-blue-400 border border-blue-600 dark:border-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 transition-colors duration-200"
                >
                    <i class="mdi mdi-close"></i>
                    {{ __("Close") }}
                </button>
            </div>
        </template>
    </v-modal>
</template>

<script setup>
import { ref, computed, defineProps } from "vue";
import VModal from "@/components/VModal.vue";
import VDeleteScope from "./DeleteScope.vue";
import VAddScope from "./AddScope.vue";

const props = defineProps({
    service: {
        type: Object,
        required: true,
    },
});

const scopes = ref([]);
const dialog = ref(false);
const loading = ref(false);

const scopesCount = computed(() => scopes.value.length);
const activeScopesCount = computed(
    () => scopes.value.filter((scope) => scope.active).length
);
const apiKeyScopesCount = computed(
    () => scopes.value.filter((scope) => scope.api_key).length
);
const publicScopesCount = computed(
    () => scopes.value.filter((scope) => scope.public).length
);
const webScopesCount = computed(
    () => scopes.value.filter((scope) => scope.web).length
);

const open = async () => {
    dialog.value = true;
    await getScopes();
};

const getScopes = async () => {
    loading.value = true;
    try {
        const res = await $server.get(props.service.links.scopes, {
            params: {
                per_page: 50,
            },
        });
        if (res.status == 200) {
            scopes.value = res.data.data;
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    } finally {
        loading.value = false;
    }
};

const copyToClipboard = async (text) => {
    try {
        await navigator.clipboard.writeText(text);
        $notify.success(__("GSR_ID copied to clipboard"));
    } catch (err) {
        $notify.error(__("Failed to copy to clipboard"));
    }
};
</script>
