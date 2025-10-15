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
        class="relative w-12 h-12 gap-2 border border-blue-600 px-4 py-2 text-blue-600 rounded-full hover:bg-blue-600 hover:text-white transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-200"
    >
        <i class="mdi mdi-shield-lock-open-outline"></i>
    </button>

    <v-modal
        v-model="dialog"
        :title="__('Scope Management')"
        panel-class="w-full lg:w-7xl"
    >
        <template #body>
            <!-- Header -->
            <div class="text-gray-700 p-6">
                <div
                    class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4"
                >
                    <div class="flex-1">
                        <p class="text-gray-700 mt-1">
                            {{ __("Managing permissions for:") }}
                            <strong>{{ __(service.name) }}</strong>
                        </p>
                        <div
                            class="flex items-center gap-1 text-gray-700 text-sm mt-1"
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
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8"
                >
                    <div
                        class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center"
                    >
                        <div class="text-2xl font-bold text-blue-700">
                            {{ scopesCount }}
                        </div>
                        <div class="text-sm text-blue-600">
                            {{ __("Total Scopes") }}
                        </div>
                        <i class="mdi mdi-lock text-blue-500 text-xl mt-2"></i>
                    </div>

                    <div
                        class="bg-green-50 border border-green-200 rounded-lg p-4 text-center"
                    >
                        <div class="text-2xl font-bold text-green-700">
                            {{ activeScopesCount }}
                        </div>
                        <div class="text-sm text-green-600">
                            {{ __("Active") }}
                        </div>
                        <i
                            class="mdi mdi-check-circle text-green-500 text-xl mt-2"
                        ></i>
                    </div>

                    <div
                        class="bg-orange-50 border border-orange-200 rounded-lg p-4 text-center"
                    >
                        <div class="text-2xl font-bold text-orange-700">
                            {{ apiKeyScopesCount }}
                        </div>
                        <div class="text-sm text-orange-600">
                            {{ __("API Key Access") }}
                        </div>
                        <i class="mdi mdi-key text-orange-500 text-xl mt-2"></i>
                    </div>

                    <div
                        class="bg-purple-50 border border-purple-200 rounded-lg p-4 text-center"
                    >
                        <div class="text-2xl font-bold text-purple-700">
                            {{ publicScopesCount }}
                        </div>
                        <div class="text-sm text-purple-600">
                            {{ __("Public Access") }}
                        </div>
                        <i
                            class="mdi mdi-earth text-purple-500 text-xl mt-2"
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
                        class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow"
                    >
                        <!-- Card Header -->
                        <div
                            class="bg-gray-50 p-4 rounded-t-lg border-b border-gray-200"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <div
                                        class="flex items-center gap-2 text-blue-600 font-bold text-lg"
                                    >
                                        <i class="mdi mdi-account-key"></i>
                                        <span class="truncate">{{
                                            item.role.name
                                        }}</span>
                                    </div>
                                    <div
                                        class="flex items-center gap-1 text-gray-500 text-sm mt-1"
                                    >
                                        <i class="mdi mdi-account-group"></i>
                                        {{ item.service.group.name }}
                                    </div>
                                </div>
                                <button
                                    @click="copyToClipboard(item.gsr_id)"
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                >
                                    <i class="mdi mdi-content-copy"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Scope Details -->
                        <div class="p-4 space-y-4">
                            <div>
                                <div class="text-sm text-gray-500 mb-1">
                                    {{ __("GSR_ID") }}
                                </div>
                                <div
                                    class="font-mono text-sm font-medium bg-gray-100 px-2 py-1 rounded"
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
                                                    ? 'mdi mdi-key-check text-green-500'
                                                    : 'mdi mdi-key-remove text-red-500'
                                            "
                                        ></i>
                                        <span class="text-sm">{{
                                            __("API Key Access")
                                        }}</span>
                                    </div>
                                    <span
                                        :class="
                                            item.api_key
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-gray-100 text-gray-600'
                                        "
                                        class="px-2 py-1 rounded-full text-xs"
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
                                                item.active
                                                    ? 'mdi mdi-check-circle text-green-500'
                                                    : 'mdi mdi-close-circle text-red-500'
                                            "
                                        ></i>
                                        <span class="text-sm">{{
                                            __("Active Status")
                                        }}</span>
                                    </div>
                                    <span
                                        :class="
                                            item.active
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-gray-100 text-gray-600'
                                        "
                                        class="px-2 py-1 rounded-full text-xs"
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
                                                    ? 'mdi mdi-earth text-blue-500'
                                                    : 'mdi mdi-earth-off text-gray-500'
                                            "
                                        ></i>
                                        <span class="text-sm">{{
                                            __("Public Access")
                                        }}</span>
                                    </div>
                                    <span
                                        :class="
                                            item.public
                                                ? 'bg-blue-100 text-blue-800'
                                                : 'bg-gray-100 text-gray-600'
                                        "
                                        class="px-2 py-1 rounded-full text-xs"
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
                            class="flex justify-end gap-2 p-4 border-t border-gray-200"
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
                        class="mdi mdi-lock-off-outline text-6xl text-gray-300"
                    ></i>
                    <div class="text-xl text-gray-500 mt-4">
                        {{ __("No scopes configured") }}
                    </div>
                    <div class="text-gray-400 mb-6">
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
                class="flex justify-end gap-3 p-6 border-t border-gray-200 bg-gray-50"
            >
                <button
                    @click="dialog = false"
                    class="flex items-center gap-2 px-6 py-2 text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-colors"
                >
                    <i class="mdi mdi-close"></i>
                    {{ __("Close") }}
                </button>
            </div>
        </template>
    </v-modal>
</template>

<script>
import VModal from "@/components/VModal.vue";
import VDeleteScope from "./DeleteScope.vue";
import VAddScope from "./AddScope.vue";

export default {
    props: ["service"],

    components: {
        VAddScope,
        VDeleteScope,
        VModal,
    },

    data() {
        return {
            scopes: [],
            dialog: false,
            loading: false,
        };
    },

    computed: {
        scopesCount() {
            return this.scopes.length;
        },
        activeScopesCount() {
            return this.scopes.filter((scope) => scope.active).length;
        },
        apiKeyScopesCount() {
            return this.scopes.filter((scope) => scope.api_key).length;
        },
        publicScopesCount() {
            return this.scopes.filter((scope) => scope.public).length;
        },
    },

    methods: {
        open() {
            this.getScopes();
            this.dialog = true;
        },

        async getScopes() {
            this.loading = true;
            try {
                const res = await this.$server.get(this.service.links.scopes);
                if (res.status == 200) {
                    this.scopes = res.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },

        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                $notify.success(__("GSR_ID copied to clipboard"));
            } catch (err) {
                $notify.error(__("Failed to copy to clipboard"));
            }
        },
    },
};
</script>
