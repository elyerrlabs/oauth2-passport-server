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
        panel-class="w-full lg:7xl"
        :title="__('Generate New API Key')"
    >
        <template #body>
            <!-- Token Display Section (visible solo cuando hay token) -->
            <div v-if="token" class="space-y-6">
                <div
                    class="bg-linear-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 rounded-xl p-6 space-y-4"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="p-2 bg-green-100 dark:bg-green-800 rounded-full"
                        >
                            <svg
                                class="w-6 h-6 text-green-600 dark:text-green-400"
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
                        </div>
                        <div>
                            <h3
                                class="text-lg font-semibold text-green-800 dark:text-green-300"
                            >
                                {{ __("API Key Generated Successfully!") }}
                            </h3>
                            <p
                                class="text-sm text-green-600 dark:text-green-400 mt-1"
                            >
                                {{ __("Your API key has been created") }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label
                            class="text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            {{ __("Key Name") }}
                        </label>
                        <div
                            class="bg-gray-100 dark:bg-gray-800 rounded-lg p-3"
                        >
                            <p
                                class="text-gray-900 dark:text-white font-medium"
                            >
                                {{ form.name }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2"
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
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"
                                />
                            </svg>
                            {{ __("Your API Key") }}
                        </label>
                        <div
                            class="bg-white dark:bg-gray-900 border-2 border-green-300 dark:border-green-700 rounded-lg p-4 flex items-center justify-between gap-3 cursor-pointer hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200 group shadow-sm"
                            @click="copyToClipboard(token.accessToken)"
                            role="button"
                            :aria-label="__('Copy API key to clipboard')"
                        >
                            <code
                                class="font-mono text-sm text-gray-800 dark:text-gray-200 break-all flex-1 select-all"
                                >{{ token.accessToken }}</code
                            >
                            <div
                                class="flex items-center gap-2 text-green-600 dark:text-green-400"
                            >
                                <span
                                    class="text-xs hidden sm:inline group-hover:underline"
                                    >{{ __("Copy") }}</span
                                >
                                <svg
                                    class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform"
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
                        </div>
                    </div>

                    <div
                        class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4"
                    >
                        <div class="flex items-start gap-3">
                            <svg
                                class="w-5 h-5 text-yellow-600 dark:text-yellow-500 mt-0.5 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                />
                            </svg>
                            <div
                                class="text-sm text-yellow-700 dark:text-yellow-300"
                            >
                                <p class="font-medium mb-1">
                                    {{ __("Important: Save this key now") }}
                                </p>
                                <p>
                                    {{
                                        __(
                                            "This API key won't be shown again. Make sure to copy and store it securely.",
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Content (visible solo cuando NO hay token) -->
            <div v-else class="space-y-6">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{
                            __(
                                "Create a new API key with specific permissions. Only grant the minimum required access.",
                            )
                        }}
                    </p>
                </div>

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
                    <p class="text-gray-500 dark:text-gray-400 text-xs">
                        {{
                            __(
                                "Choose a descriptive name to identify this API key",
                            )
                        }}
                    </p>
                </div>

                <!-- Scopes Section -->
                <div
                    v-if="!loadingScopes && scopes.length > 0"
                    class="space-y-4"
                >
                    <div
                        class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-2"
                    >
                        <label
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2"
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
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                />
                            </svg>
                            {{ __("Permissions") }}
                        </label>
                        <span
                            class="text-xs font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-2.5 py-1 rounded-full"
                        >
                            {{ selectedScopesCount }}
                            {{ __("selected") }}
                        </span>
                    </div>

                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        <div
                            v-for="(services, group) in groupedScopes"
                            :key="group"
                            class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden bg-white dark:bg-gray-800"
                        >
                            <button
                                @click="toggleGroup(group)"
                                class="w-full px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center justify-between text-left transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-inset"
                                :aria-expanded="expandedGroups.includes(group)"
                                :aria-controls="`group-${group}`"
                            >
                                <span
                                    class="font-semibold text-gray-900 dark:text-white capitalize text-sm"
                                    >{{ formatGroupName(group) }}</span
                                >
                                <div class="flex items-center gap-3">
                                    <span
                                        class="text-xs bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300 px-2 py-0.5 rounded-full"
                                    >
                                        {{ getGroupScopeCount(services) }}
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
                                class="px-4 py-3 bg-gray-50 dark:bg-gray-800/50 space-y-4 border-t border-gray-200 dark:border-gray-700"
                            >
                                <div
                                    v-for="(roles, service) in services"
                                    :key="service"
                                    class="space-y-2"
                                >
                                    <h5
                                        class="font-medium text-gray-700 dark:text-gray-300 text-xs uppercase tracking-wider flex items-center gap-2"
                                    >
                                        <span
                                            class="w-1.5 h-1.5 bg-indigo-400 rounded-full"
                                        ></span>
                                        {{ formatServiceName(service) }}
                                    </h5>

                                    <div class="grid grid-cols-1 gap-2 pl-2">
                                        <label
                                            v-for="scope in roles"
                                            :key="scope.id"
                                            class="flex items-start gap-3 p-2 rounded cursor-pointer group hover:bg-white dark:hover:bg-gray-700 transition-all duration-150"
                                            :class="{
                                                'bg-white dark:bg-gray-700 shadow-sm':
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
                                                class="mt-0.5 text-indigo-600 dark:text-indigo-500 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700"
                                            />
                                            <div class="flex-1 min-w-0">
                                                <span
                                                    class="block text-sm font-medium text-gray-900 dark:text-white"
                                                >
                                                    {{ getRoleName(scope) }}
                                                </span>
                                                <p
                                                    class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"
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
                    class="flex items-center justify-center py-12"
                >
                    <div class="flex flex-col items-center gap-3">
                        <svg
                            class="animate-spin h-8 w-8 text-indigo-600 dark:text-indigo-400"
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
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __("Loading permissions...") }}
                        </span>
                    </div>
                </div>

                <!-- Empty State -->
                <div
                    v-else-if="!loadingScopes && scopes.length === 0"
                    class="flex items-center justify-center py-12"
                >
                    <div class="text-center text-gray-500 dark:text-gray-400">
                        <svg
                            class="w-12 h-12 mx-auto mb-3 text-gray-400 dark:text-gray-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                        <p class="text-sm">
                            {{ __("No permissions available at this time.") }}
                        </p>
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
                    v-if="!token"
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
                    {{ __("Select permissions and generate your API key") }}
                </div>
                <div
                    v-else
                    class="text-sm text-green-600 dark:text-green-400 flex items-center gap-2"
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
                            d="M5 13l4 4L19 7"
                        />
                    </svg>
                    {{ __("API key ready to use") }}
                </div>
                <div class="flex gap-3">
                    <v-button
                        @click="close"
                        :disabled="loading"
                        :label="__('Close')"
                        variant="secondary"
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
                        variant="primary"
                    />
                </div>
            </div>
        </template>
    </v-modal>
</template>

<script setup>
import { ref, computed } from "vue";
import VModal from "@/components/VModal.vue";
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

    if (Object.keys(groupedScopes.value).length > 0) {
        const firstGroup = Object.keys(groupedScopes.value)[0];
        expandedGroups.value.push(firstGroup);
    }
};

const close = () => {
    dialog.value = false;
    token.value = null;
    errors.value = {};
    form.value.name = "";
    form.value.scopes = [];
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
        const res = await $server.post(page.props.routes.tokens, form.value);

        if (res.status == 200) {
            token.value = res.data;
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
