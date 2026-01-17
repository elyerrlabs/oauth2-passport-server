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
    <!-- Create Button -->
    <button
        @click="open"
        class="flex group justify-center items-center gap-2 px-4 py-2.5 bg-indigo-600 dark:bg-indigo-700 text-white rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5"
    >
        <svg
            class="w-5 h-5 transform group-hover:scale-110 transition-transform duration-200"
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
    <v-modal v-model="dialog" panel-class="w-full max-w-7xl">
        <template #body>
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg transition-colors duration-200"
                    >
                        <svg
                            class="w-5 h-5 text-indigo-600 dark:text-indigo-400"
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
                        <h2
                            class="text-lg font-semibold text-gray-900 dark:text-white"
                        >
                            {{ __("Generate New API Key") }}
                        </h2>
                        <p
                            class="text-sm text-gray-500 dark:text-gray-400 mt-1"
                        >
                            {{
                                __(
                                    "Create a new API key with specific permissions",
                                )
                            }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="space-y-6">
                <!-- Key Name Section -->
                <div class="space-y-3">
                    <label
                        for="key-name"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        {{ __("Key Name") }}
                        <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="key-name"
                        v-model="form.name"
                        type="text"
                        :placeholder="
                            __('e.g., Production Server, Mobile App, etc.')
                        "
                        :class="[
                            'w-full px-3 py-2.5 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400',
                            errors.name
                                ? 'border-red-300 dark:border-red-500 bg-red-50 dark:bg-red-900/20'
                                : 'border-gray-300 dark:border-gray-600',
                        ]"
                        :disabled="loading"
                    />
                    <v-error :error="errors.name" />
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        {{
                            __(
                                "Choose a descriptive name to identify this API key",
                            )
                        }}
                    </p>
                </div>

                <!-- Token Display -->
                <div
                    v-if="token"
                    class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 space-y-3 animate-fade-in"
                >
                    <div class="flex items-center gap-2">
                        <svg
                            class="w-5 h-5 text-green-600 dark:text-green-400 shrink-0"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <h4
                            class="text-green-800 dark:text-green-300 font-medium text-sm"
                        >
                            {{ __("API Key Generated Successfully!") }}
                        </h4>
                    </div>

                    <div class="space-y-2">
                        <p
                            class="text-green-700 dark:text-green-300 text-sm font-medium"
                        >
                            {{ __("Your new API key:") }}
                        </p>
                        <div
                            class="bg-white dark:bg-gray-800 border border-green-300 dark:border-green-700 rounded-lg p-3 flex items-center justify-between gap-3 cursor-pointer hover:bg-green-50 dark:hover:bg-green-900/30 transition-all duration-150 group"
                            @click="copyToClipboard(token.accessToken)"
                            role="button"
                            :aria-label="__('Copy API key to clipboard')"
                        >
                            <code
                                class="font-mono text-sm text-gray-800 dark:text-gray-200 break-all flex-1"
                                >{{ token.accessToken }}</code
                            >
                            <svg
                                class="w-5 h-5 text-green-600 dark:text-green-400 shrink-0 group-hover:scale-110 transition-transform"
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
                            class="text-green-600 dark:text-green-400 text-xs flex items-center gap-1"
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
                                    "Click to copy the API key. Save it securely - you won't be able to see it again!",
                                )
                            }}
                        </p>
                    </div>
                </div>

                <!-- Scopes Section -->
                <div
                    v-if="!loadingScopes && scopes.length > 0"
                    class="space-y-4"
                >
                    <div class="flex items-center justify-between">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            {{ __("Permissions") }}
                        </label>
                        <span
                            class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded transition-colors duration-200"
                        >
                            {{ selectedScopesCount }}
                            {{ __("selected") }}
                        </span>
                    </div>

                    <div class="space-y-3">
                        <div
                            v-for="(services, group) in groupedScopes"
                            :key="group"
                            class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden transition-all duration-200 hover:shadow-sm"
                        >
                            <button
                                @click="toggleGroup(group)"
                                class="w-full px-4 py-3 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center justify-between text-left transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-inset"
                                :aria-expanded="expandedGroups.includes(group)"
                                :aria-controls="`group-${group}`"
                            >
                                <span
                                    class="font-medium text-gray-900 dark:text-white capitalize"
                                    >{{ formatGroupName(group) }}</span
                                >
                                <div class="flex items-center gap-3">
                                    <span
                                        class="text-xs bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300 px-2 py-1 rounded-full transition-colors duration-200"
                                    >
                                        {{ getGroupScopeCount(services) }}
                                        {{ __("permissions") }}
                                    </span>
                                    <svg
                                        class="w-4 h-4 text-gray-500 dark:text-gray-400 transition-transform duration-200"
                                        :class="{
                                            'rotate-180':
                                                expandedGroups.includes(group),
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
                                class="px-4 py-3 bg-white dark:bg-gray-800 space-y-4 border-t border-gray-200 dark:border-gray-700"
                            >
                                <div
                                    v-for="(roles, service) in services"
                                    :key="service"
                                    class="space-y-3"
                                >
                                    <h5
                                        class="font-medium text-gray-800 dark:text-gray-200 text-sm capitalize flex items-center gap-2"
                                    >
                                        <svg
                                            class="w-4 h-4 text-gray-500 dark:text-gray-400"
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
                                        {{ formatServiceName(service) }}
                                    </h5>

                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-3"
                                    >
                                        <label
                                            v-for="scope in roles"
                                            :key="scope.id"
                                            class="flex items-start gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-150 cursor-pointer group"
                                            :class="{
                                                'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-700':
                                                    form.scopes.includes(
                                                        scope.id,
                                                    ),
                                            }"
                                        >
                                            <input
                                                type="checkbox"
                                                v-model="form.scopes"
                                                :value="scope.id"
                                                :disabled="loading"
                                                class="mt-0.5 text-indigo-600 dark:text-indigo-500 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 transition-colors duration-200"
                                            />
                                            <div class="flex-1 min-w-0">
                                                <span
                                                    class="block text-sm font-medium text-gray-900 dark:text-white capitalize"
                                                >
                                                    {{ getRoleName(scope) }}
                                                </span>
                                                <p
                                                    class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed"
                                                >
                                                    {{ scope.description }}
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
                    v-if="loadingScopes"
                    class="flex items-center justify-center py-8"
                >
                    <div
                        class="flex items-center gap-3 text-gray-500 dark:text-gray-400"
                    >
                        <svg
                            class="animate-spin h-5 w-5 text-indigo-600 dark:text-indigo-400"
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

                <!-- Empty State -->
                <div
                    v-else-if="!loadingScopes && scopes.length === 0"
                    class="flex items-center justify-center py-8"
                >
                    <div
                        class="text-sm text-gray-500 dark:text-gray-400 text-center"
                    >
                        {{
                            __(
                                "No services are currently assigned. You can still generate basic tokens for core features or testing purposes.",
                            )
                        }}
                    </div>
                </div>

                <!-- Security Notice -->
                <div
                    class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4"
                >
                    <div class="flex items-start gap-3">
                        <svg
                            class="w-5 h-5 text-blue-500 dark:text-blue-400 mt-0.5 shrink-0"
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
                        <div class="text-sm text-blue-700 dark:text-blue-300">
                            <p class="font-medium mb-1">
                                {{ __("Security Best Practices") }}
                            </p>
                            <p>
                                {{
                                    __(
                                        "Only grant the minimum permissions required. Regularly review and rotate your API keys.",
                                    )
                                }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div
                class="flex flex-col sm:flex-row justify-between gap-3 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700"
            >
                <div
                    class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2"
                >
                    <svg
                        class="w-4 h-4 text-gray-400 dark:text-gray-500"
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
                            "Make sure to copy your API key before closing this dialog",
                        )
                    }}
                </div>
                <div class="flex gap-3">
                    <button
                        @click="close"
                        :disabled="loading"
                        class="px-4 py-2.5 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ token ? __("Done") : __("Cancel") }}
                    </button>
                    <button
                        v-if="!token"
                        @click="create"
                        :disabled="loading || !form.name.trim()"
                        :class="[
                            'inline-flex items-center gap-2 px-4 py-2.5 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm',
                            loading || !form.name.trim()
                                ? 'bg-indigo-400 dark:bg-indigo-500'
                                : 'bg-indigo-600 dark:bg-indigo-700 hover:bg-indigo-700 dark:hover:bg-indigo-600',
                        ]"
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
        </template>
    </v-modal>
</template>

<script>
import VModal from "@/components/VModal.vue";
import VError from "@/components/VError.vue";

export default {
    emits: ["created"],
    components: {
        VModal,
        VError,
    },

    data() {
        return {
            loading: false,
            form: {
                name: "",
                scopes: [],
                expiration_date: "",
            },
            errors: {},
            scopes: [],
            dialog: false,
            expandedGroups: [],
            loadingScopes: true,
            token: null,
        };
    },

    computed: {
        groupedScopes() {
            const grouped = {};

            this.scopes.forEach((scope) => {
                if (!scope.id || typeof scope.id !== "string") {
                    console.warn("Scope without id or invalid id:", scope);
                    return;
                }

                const parts = scope.id.split(":");
                if (parts.length !== 3) {
                    console.warn("Invalid scope id format:", scope.id);
                    return;
                }

                const [group, service, role] = parts;

                if (!grouped[group]) {
                    grouped[group] = {};
                }

                if (!grouped[group][service]) {
                    grouped[group][service] = [];
                }

                grouped[group][service].push({
                    ...scope,
                    roleName: role,
                });
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
            this.loadingScopes = true;

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

        formatGroupName(group) {
            return group
                .replace(/-/g, " ")
                .replace(/\b\w/g, (l) => l.toUpperCase());
        },

        formatServiceName(service) {
            return service
                .replace(/-/g, " ")
                .replace(/\b\w/g, (l) => l.toUpperCase());
        },

        getRoleName(scope) {
            if (!scope.roleName) {
                // Extraer el role del id si no está en scope.roleName
                const parts = scope.id.split(":");
                if (parts.length === 3) {
                    return parts[2]
                        .replace(/-/g, " ")
                        .replace(/\b\w/g, (l) => l.toUpperCase());
                }
                return scope.id;
            }

            return scope.roleName
                .replace(/-/g, " ")
                .replace(/\b\w/g, (l) => l.toUpperCase());
        },

        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                this.$notify.success(this.__("API key copied to clipboard"));
            } catch (e) {
                this.$notify.error(this.__("Failed to copy to clipboard"));
            }
        },

        async create() {
            if (!this.form.name.trim()) {
                this.errors = { name: this.__("Key name is required") };
                return;
            }

            this.loading = true;
            this.token = null;
            this.errors = {};

            try {
                const res = await this.$server.post(
                    this.$page.props.route,
                    this.form,
                );

                if (res.status == 200) {
                    this.token = res.data;
                    this.$emit("created");
                    this.$notify.success(
                        this.__("API key generated successfully"),
                    );
                }
            } catch (e) {
                if (e?.response?.status == 422) {
                    this.errors = e.response.data.errors;
                }

                if (e?.response?.data?.message) {
                    this.$notify.error(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },

        async getScopes() {
            try {
                const res = await this.$server.get("/oauth/scopes");

                if (res.status == 200) {
                    this.scopes = res.data;
                    this.loadingScopes = false;
                }
            } catch (error) {
                this.$notify.error(this.__("Failed to load permissions"));
                this.loadingScopes = false;
            }
        },

        getGroupScopeCount(services) {
            return Object.values(services).reduce(
                (total, roles) => total + roles.length,
                0,
            );
        },
    },
};
</script>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
