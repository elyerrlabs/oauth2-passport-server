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
    <v-button
        @click="open"
        :label="__('Create New API Key')"
        variant="secondary"
    />

    <!-- Dialog -->
    <v-modal
        v-model="dialog"
        panel-class="w-full max-w-7xl"
        :title="__('Generate New API Key')"
    >
        <template #body>
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div>
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
                    <v-input
                        :label="__('Key Name')"
                        v-model="form.name"
                        :placeholder="
                            __('e.g., Production Server, Mobile App, etc.')
                        "
                        :disabled="loading"
                        :error="errors.name"
                    />
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
                    <v-button
                        @click="close"
                        :disabled="loading"
                        :label="__('Cancel')"
                        variant="danger"
                    />
                    <v-button
                        v-if="!token"
                        @click="create"
                        :disabled="loading || !form.name.trim()"
                        :label="
                            loading
                                ? __('Generating...')
                                : __('Generate API Key')
                        "
                    />
                </div>
            </div>
        </template>
    </v-modal>
</template>

<script setup>
import { ref, computed } from "vue";
import VModal from "@/components/VModal.vue";
import VError from "@/components/VError.vue";
import VButton from "@/components/VButton.vue";
import VInput from "@/components/VInput.vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const emits = defineEmits(["created"]);
const loading = ref(false);
const form = ref({
    name: "",
    scopes: [],
    expiration_date: "",
});
const errors = ref({});
const scopes = ref([]);
const dialog = ref(false);
const expandedGroups = ref([]);
const loadingScopes = ref(true);
const token = ref(null);

const groupedScopes = computed(() => {
    const grouped = {};

    scopes.value.forEach((scope) => {
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
});

const selectedScopesCount = computed(() => {
    return form.value.scopes.length;
});

const open = async () => {
    dialog.value = true;
    token.value = null;
    errors.value = {};
    form.value.name = "";
    form.value.scopes = [];
    expandedGroups.value = [];
    loadingScopes.value = true;

    await getScopes();

    if (Object.keys(groupedScopes).length > 0) {
        const firstGroup = Object.keys(groupedScopes)[0];
        expandedGroups.value.push(firstGroup);
    }
};

const close = () => {
    dialog.value = false;
    token.value = null;
    errors.value = {};
};

const toggleGroup = (group) => {
    const index = expandedGroups.value.indexOf(group);
    if (index > -1) {
        expandedGroups.value.splice(index, 1);
    } else {
        expandedGroups.value.push(group);
    }
};

const formatGroupName = (group) => {
    return group.replace(/-/g, " ").replace(/\b\w/g, (l) => l.toUpperCase());
};

const formatServiceName = (service) => {
    return service.replace(/-/g, " ").replace(/\b\w/g, (l) => l.toUpperCase());
};

const getRoleName = (scope) => {
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
};

const copyToClipboard = async (text) => {
    try {
        await navigator.clipboard.writeText(text);
        $notify.success(__("API key copied to clipboard"));
    } catch (e) {
        $notify.error(__("Failed to copy to clipboard"));
    }
};

const create = async () => {
    if (!form.value.name.trim()) {
        errors.value = { name: __("Key name is required") };
        return;
    }

    loading.value = true;
    token.value = null;
    errors.value = {};

    try {
        const res = await $server.post(page.props.route, form.value);

        if (res.status == 200) {
            token = res.data;
            emits("created");
            $notify.success(__("API key generated successfully"));
        }
    } catch (e) {
        if (e?.response?.status == 422) {
            errors.value = e.response.data.errors;
        }

        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    } finally {
        loading.value = false;
    }
};

const getScopes = async () => {
    try {
        const res = await $server.get("/oauth/scopes");

        if (res.status == 200) {
            scopes.value = res.data;
            loadingScopes.value = false;
        }
    } catch (error) {
        $notify.error(__("Failed to load permissions"));
        loadingScopes.value = false;
    }
};

const getGroupScopeCount = (services) => {
    return Object.values(services).reduce(
        (total, roles) => total + roles.length,
        0,
    );
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
