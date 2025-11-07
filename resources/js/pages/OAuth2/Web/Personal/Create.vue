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
    <!-- Create Button -->
    <button
        @click="open"
        class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200 shadow-sm"
    >
        <svg
            class="w-5 h-5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 4v16m8-8H4"
            />
        </svg>
        <span class="font-medium">{{ __("Create New API Key") }}</span>
    </button>

    <!-- Dialog -->
    <transition
        enter-active-class="transition-opacity duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="dialog"
            class="fixed inset-0 z-50 overflow-y-auto"
            role="dialog"
            aria-modal="true"
            :aria-label="__('Generate New API Key')"
        >
            <!-- Backdrop -->
            <div
                class="fixed inset-0 bg-black/80 transition-opacity"
                @click="close"
            ></div>

            <!-- Dialog Panel -->
            <div class="flex min-h-full items-center justify-center p-4">
                <div
                    class="relative bg-white rounded-xl shadow-xl w-full max-w-7xl max-h-[90vh] overflow-hidden transform transition-all"
                >
                    <!-- Header -->
                    <div
                        class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-white"
                    >
                        <div class="flex items-center gap-3">
                            <div
                                class="flex items-center justify-center w-10 h-10 bg-indigo-100 rounded-lg"
                            >
                                <svg
                                    class="w-5 h-5 text-indigo-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">
                                    {{ __("Generate New API Key") }}
                                </h2>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{
                                        __(
                                            "Create a new API key with specific permissions"
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                        <button
                            @click="close"
                            class="rounded-lg p-2 hover:bg-gray-100 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            :aria-label="__('Close dialog')"
                        >
                            <svg
                                class="w-5 h-5 text-gray-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>

                    <!-- Form Content -->
                    <div
                        class="px-6 py-4 space-y-6 overflow-y-auto max-h-[calc(90vh-180px)]"
                    >
                        <!-- Key Name Section -->
                        <div class="space-y-3">
                            <label
                                for="key-name"
                                class="block text-sm font-medium text-gray-700"
                            >
                                {{ __("Key Name") }}
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="key-name"
                                v-model="form.name"
                                type="text"
                                :placeholder="
                                    __(
                                        'e.g., Production Server, Mobile App, etc.'
                                    )
                                "
                                :class="[
                                    'w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200',
                                    errors.name
                                        ? 'border-red-300 bg-red-50'
                                        : 'border-gray-300',
                                ]"
                                :disabled="loading"
                            />
                            <p
                                v-if="errors.name"
                                class="text-red-600 text-sm flex items-center gap-1"
                            >
                                <svg
                                    class="w-4 h-4 flex-shrink-0"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                {{ errors.name }}
                            </p>
                            <p class="text-gray-500 text-sm">
                                {{
                                    __(
                                        "Choose a descriptive name to identify this API key"
                                    )
                                }}
                            </p>
                        </div>

                        <!-- Token Display -->
                        <div
                            v-if="token"
                            class="bg-green-50 border border-green-200 rounded-lg p-4 space-y-3"
                        >
                            <div class="flex items-center gap-2">
                                <svg
                                    class="w-5 h-5 text-green-600 flex-shrink-0"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                <h4 class="text-green-800 font-medium text-sm">
                                    {{ __("API Key Generated Successfully!") }}
                                </h4>
                            </div>

                            <div class="space-y-2">
                                <p class="text-green-700 text-sm font-medium">
                                    {{ __("Your new API key:") }}
                                </p>
                                <div
                                    class="bg-white border border-green-300 rounded-lg p-3 flex items-center justify-between gap-3 cursor-pointer hover:bg-green-25 transition-colors duration-150 group"
                                    @click="copyToClipboard(token.accessToken)"
                                    role="button"
                                    :aria-label="
                                        __('Copy API key to clipboard')
                                    "
                                >
                                    <code
                                        class="font-mono text-sm text-gray-800 break-all flex-1"
                                        >{{ token.accessToken }}</code
                                    >
                                    <svg
                                        class="w-5 h-5 text-green-600 flex-shrink-0 group-hover:scale-110 transition-transform"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-2"
                                        />
                                    </svg>
                                </div>
                                <p
                                    class="text-green-600 text-xs flex items-center gap-1"
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
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                    {{
                                        __(
                                            "Click to copy the API key. Save it securely - you won't be able to see it again!"
                                        )
                                    }}
                                </p>
                            </div>
                        </div>

                        <!-- Scopes Section -->
                        <div v-if="scopes.length > 0" class="space-y-4">
                            <div class="flex items-center justify-between">
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    {{ __("Permissions") }}
                                </label>
                                <span
                                    class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded"
                                >
                                    {{ selectedScopesCount }}
                                    {{ __("selected") }}
                                </span>
                            </div>

                            <div class="space-y-3">
                                <div
                                    v-for="(services, group) in groupedScopes"
                                    :key="group"
                                    class="border border-gray-200 rounded-lg overflow-hidden"
                                >
                                    <button
                                        @click="toggleGroup(group)"
                                        class="w-full px-4 py-3 bg-gray-50 hover:bg-gray-100 flex items-center justify-between text-left transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-inset"
                                        :aria-expanded="
                                            expandedGroups.includes(group)
                                        "
                                        :aria-controls="`group-${group}`"
                                    >
                                        <span
                                            class="font-medium text-gray-900 capitalize"
                                            >{{ group }}</span
                                        >
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="text-xs bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full"
                                            >
                                                {{
                                                    getGroupScopeCount(services)
                                                }}
                                                {{ __("permissions") }}
                                            </span>
                                            <svg
                                                class="w-4 h-4 text-gray-500 transition-transform duration-200"
                                                :class="{
                                                    'rotate-180':
                                                        expandedGroups.includes(
                                                            group
                                                        ),
                                                }"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M19 9l-7 7-7-7"
                                                />
                                            </svg>
                                        </div>
                                    </button>

                                    <div
                                        v-show="expandedGroups.includes(group)"
                                        :id="`group-${group}`"
                                        class="px-4 py-3 bg-white space-y-4 border-t border-gray-200"
                                    >
                                        <div
                                            v-for="(roles, service) in services"
                                            :key="service"
                                            class="space-y-3"
                                        >
                                            <h5
                                                class="font-medium text-gray-800 text-sm capitalize flex items-center gap-2"
                                            >
                                                <svg
                                                    class="w-4 h-4 text-gray-500"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                                    />
                                                </svg>
                                                {{ service }}
                                            </h5>

                                            <div
                                                class="grid grid-cols-1 md:grid-cols-2 gap-3"
                                            >
                                                <label
                                                    v-for="role in roles"
                                                    :key="role.id"
                                                    class="flex items-start gap-3 p-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors duration-150 cursor-pointer"
                                                    :class="{
                                                        'bg-blue-50 border-blue-200':
                                                            form.scopes.includes(
                                                                role.id
                                                            ),
                                                    }"
                                                >
                                                    <input
                                                        type="checkbox"
                                                        v-model="form.scopes"
                                                        :value="role.id"
                                                        :disabled="loading"
                                                        class="mt-0.5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                                    />
                                                    <div class="flex-1 min-w-0">
                                                        <span
                                                            class="block text-sm font-medium text-gray-900 capitalize"
                                                        >
                                                            {{ role.name }}
                                                        </span>
                                                        <p
                                                            class="text-xs text-gray-500 mt-1 leading-relaxed"
                                                        >
                                                            {{
                                                                role.description
                                                            }}
                                                        </p>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Loading State for Scopes -->
                        <div
                            v-if="scopes.length === 0 && dialog"
                            class="flex items-center justify-center py-8"
                        >
                            <div class="flex items-center gap-3 text-gray-500">
                                <svg
                                    class="animate-spin h-5 w-5 text-indigo-600"
                                    xmlns="http://www.w3.org/2000/svg"
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
                                <span class="text-sm">
                                    {{ __("Loading permissions...") }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div
                        class="flex flex-col sm:flex-row justify-between gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50"
                    >
                        <div
                            class="text-sm text-gray-500 flex items-center gap-2"
                        >
                            <svg
                                class="w-4 h-4 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            {{
                                __(
                                    "Make sure to copy your API key before closing this dialog"
                                )
                            }}
                        </div>
                        <div class="flex gap-3">
                            <button
                                @click="close"
                                :disabled="loading"
                                class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {{ token ? __("Done") : __("Cancel") }}
                            </button>
                            <button
                                v-if="!token"
                                @click="create"
                                :disabled="loading || !form.name.trim()"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
                            >
                                <svg
                                    v-if="loading"
                                    class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg"
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
                                <svg
                                    v-else
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"
                                    />
                                </svg>
                                <span>{{
                                    loading
                                        ? __("Generating...")
                                        : __("Generate API Key")
                                }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    emits: ["created"],
    data() {
        return {
            form: {
                name: "",
                scopes: [],
                expiration_date: "",
            },
            errors: {},
            scopes: [],
            dialog: false,
            expandedGroups: [],
            loading: false,
            token: null,
        };
    },

    computed: {
        groupedScopes() {
            const grouped = {};
            this.scopes.forEach((scope) => {
                if (scope.id) {
                    const [group, service, role] = scope.id.split(":");
                    if (!grouped[group]) grouped[group] = {};
                    if (!grouped[group][service]) grouped[group][service] = [];
                    Object.assign(scope, { name: role });
                    grouped[group][service].push(scope);
                }
            });
            return grouped;
        },

        selectedScopesCount() {
            return this.form.scopes.length;
        },
    },

    methods: {
        async open() {
            this.dialog = true;
            this.token = null;
            this.errors = {};
            this.form.name = "";
            this.form.scopes = [];
            this.expandedGroups = [];

            await this.getScopes();

            if (Object.keys(this.groupedScopes).length > 0) {
                const firstGroup = Object.keys(this.groupedScopes)[0];
                this.expandedGroups.push(firstGroup);
            }
        },

        close() {
            this.dialog = false;
            this.token = null;
            this.errors = {};
        },

        toggleGroup(group) {
            const index = this.expandedGroups.indexOf(group);
            if (index > -1) {
                this.expandedGroups.splice(index, 1);
            } else {
                this.expandedGroups.push(group);
            }
        },

        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                 $notify.success(__("API key copied to clipboard"));
            } catch (e) {
                 $notify.error(__("Failed to copy to clipboard"));
            }
        },

        async create() {
            if (!this.form.name.trim()) {
                this.errors = { name: __("Key name is required") };
                return;
            }

            this.loading = true;
            this.token = null;
            this.errors = {};

            try {
                const res = await this.$server.post(
                    this.$page.props.route,
                    this.form
                );

                if (res.status == 200) {
                    this.token = res.data;
                    this.$emit("created");
                     $notify.success(__("API key generated successfully"));
                }
            } catch (e) {
                if (e?.response?.status == 422) {
                    this.errors = e.response.data.errors;
                }

                if (e?.response?.data?.message) {
                     $notify.success(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },

        async getScopes() {
            try {
                const res = await this.$server.get("/oauth/scopes");
                this.scopes = res.data;
            } catch (error) {
                 $notify.success(__("Failed to load permissions"));
            }
        },

        getGroupScopeCount(services) {
            return Object.values(services).reduce(
                (total, roles) => total + roles.length,
                0
            );
        },
    },
};
</script>
