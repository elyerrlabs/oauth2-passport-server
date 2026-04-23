<template>
    <!-- Manage Scopes Button -->
    <button
        @click="openDialog"
        class="relative group w-10 h-10 border border-blue-600 dark:border-blue-400 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 focus:ring-offset-2"
        :title="__('Manage Access Scopes')"
    >
        <i class="mdi mdi-shield-account-outline"></i>
        <div
            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap"
        >
            {{ __("Manage Scopes") }}
        </div>
    </button>

    <v-modal
        v-model="dialog"
        :title="__('Manage Access Scopes')"
        panel-class="w-full lg:w-7xl  h-screen"
    >
        <template #body>
            <!-- Header Compacto -->
            <div
                class="flex items-center justify-between px-5 py-3 border-b border-gray-200 dark:border-gray-700"
            >
                <div class="flex items-center gap-3">
                    <i
                        class="mdi mdi-shield-account text-blue-600 dark:text-blue-400"
                    ></i>
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400"
                            >{{ __("User") }}:</span
                        >
                        <span
                            class="text-sm font-medium text-gray-900 dark:text-white ml-1"
                        >
                            {{ item.name }} {{ item.last_name }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-xs">
                    <span class="text-gray-500 dark:text-gray-400">
                        <i class="mdi mdi-folder-multiple-outline mr-1"></i
                        >{{ totalGroups }}
                    </span>
                    <span class="text-gray-500 dark:text-gray-400">
                        <i class="mdi mdi-check-circle-outline mr-1"></i
                        >{{ assignedScopesCount }}/{{ totalRoles }}
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="p-4">
                <!-- Loading -->
                <div
                    v-if="loading"
                    class="flex justify-center items-center py-12"
                >
                    <div class="flex items-center gap-2 text-gray-500">
                        <svg
                            class="animate-spin h-5 w-5 text-blue-600"
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
                        <span class="text-sm">{{ __("Loading...") }}</span>
                    </div>
                </div>

                <!-- Empty -->
                <div
                    v-else-if="!showSkeleton && scopes.length === 0"
                    class="text-center py-12"
                >
                    <i
                        class="mdi mdi-shield-off-outline text-gray-400 text-3xl mb-3"
                    ></i>
                    <p class="text-sm text-gray-500">
                        {{ __("No permissions available") }}
                    </p>
                </div>

                <!-- Groups -->
                <div v-else class="space-y-2 max-h-[60vh] overflow-y-auto pr-1">
                    <div
                        v-for="(group, groupIndex) in groupedScopes"
                        :key="groupIndex"
                        class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden bg-white dark:bg-gray-800"
                    >
                        <!-- Group Header -->
                        <div
                            @click="toggleGroup(groupIndex)"
                            class="flex items-center justify-between px-3 py-2.5 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
                        >
                            <div class="flex items-center gap-2 min-w-0">
                                <i
                                    class="mdi mdi-folder-outline text-gray-500 dark:text-gray-400 text-sm flex-shrink-0"
                                ></i>
                                <span
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate"
                                >
                                    {{ __(group.name) }}
                                </span>
                                <span
                                    class="text-xs text-gray-400 flex-shrink-0"
                                >
                                    {{ getAssignedCountForGroup(group) }}/{{
                                        getTotalRolesCountForGroup(group)
                                    }}
                                </span>
                            </div>
                            <i
                                class="mdi text-gray-400 text-sm transition-transform duration-200 flex-shrink-0"
                                :class="
                                    expandedGroups[groupIndex]
                                        ? 'mdi-chevron-up'
                                        : 'mdi-chevron-down'
                                "
                            ></i>
                        </div>

                        <!-- Progress thin bar -->
                        <div class="h-0.5 bg-gray-100 dark:bg-gray-700">
                            <div
                                class="h-full bg-green-500 transition-all duration-300"
                                :style="{
                                    width: `${getGroupProgress(group)}%`,
                                }"
                            ></div>
                        </div>

                        <!-- Services -->
                        <transition name="slide">
                            <div
                                v-show="expandedGroups[groupIndex]"
                                class="border-t border-gray-100 dark:border-gray-700"
                            >
                                <div
                                    class="p-2 space-y-1.5 bg-gray-50/50 dark:bg-gray-900/20"
                                >
                                    <div
                                        v-for="(
                                            service, serviceIndex
                                        ) in group.services"
                                        :key="serviceIndex"
                                        class="border border-gray-200 dark:border-gray-700 rounded-md overflow-hidden bg-white dark:bg-gray-800"
                                    >
                                        <!-- Service Header -->
                                        <div
                                            @click="
                                                toggleService(
                                                    groupIndex,
                                                    serviceIndex,
                                                )
                                            "
                                            class="flex items-center justify-between px-3 py-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
                                        >
                                            <div
                                                class="flex items-center gap-2 min-w-0"
                                            >
                                                <i
                                                    class="mdi mdi-cog-outline text-gray-400 text-sm flex-shrink-0"
                                                ></i>
                                                <span
                                                    class="text-sm text-gray-700 dark:text-gray-300 truncate"
                                                >
                                                    {{ __(service.name) }}
                                                </span>
                                                <span
                                                    class="text-xs text-gray-400 flex-shrink-0"
                                                >
                                                    {{
                                                        getAssignedCountForService(
                                                            service,
                                                        )
                                                    }}/{{
                                                        service.roles.length
                                                    }}
                                                </span>
                                            </div>
                                            <i
                                                class="mdi text-gray-400 text-sm transition-transform duration-200 flex-shrink-0"
                                                :class="
                                                    expandedServices[
                                                        `${groupIndex}-${serviceIndex}`
                                                    ]
                                                        ? 'mdi-chevron-up'
                                                        : 'mdi-chevron-down'
                                                "
                                            ></i>
                                        </div>

                                        <!-- Roles -->
                                        <transition name="slide">
                                            <div
                                                v-show="
                                                    expandedServices[
                                                        `${groupIndex}-${serviceIndex}`
                                                    ]
                                                "
                                                class="border-t border-gray-100 dark:border-gray-700 p-2 bg-gray-50/30 dark:bg-gray-900/20"
                                            >
                                                <div
                                                    class="grid grid-cols-1 md:grid-cols-2 gap-1.5"
                                                >
                                                    <div
                                                        v-for="role in service.roles"
                                                        :key="role.id"
                                                        class="flex items-center justify-between px-2.5 py-1.5 rounded-md transition-colors"
                                                        :class="{
                                                            'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800':
                                                                findScope(
                                                                    role?.scope
                                                                        ?.id,
                                                                ),
                                                            'hover:bg-gray-50 dark:hover:bg-gray-700/30':
                                                                !findScope(
                                                                    role?.scope
                                                                        ?.id,
                                                                ),
                                                        }"
                                                    >
                                                        <div
                                                            class="min-w-0 flex-1 mr-2"
                                                        >
                                                            <div
                                                                class="text-xs font-medium text-gray-700 dark:text-gray-300 truncate"
                                                            >
                                                                {{
                                                                    __(
                                                                        role.name,
                                                                    )
                                                                }}
                                                            </div>
                                                            <div
                                                                class="text-xs text-gray-400 truncate"
                                                            >
                                                                {{
                                                                    __(
                                                                        role.description,
                                                                    )
                                                                }}
                                                            </div>
                                                        </div>

                                                        <div
                                                            class="flex-shrink-0"
                                                        >
                                                            <v-add-scope
                                                                :item="role"
                                                                @created="
                                                                    userRoles
                                                                "
                                                                v-if="
                                                                    !findScope(
                                                                        role
                                                                            ?.scope
                                                                            ?.id,
                                                                    )
                                                                "
                                                            />
                                                            <v-delete-scope
                                                                @deleted="
                                                                    userRoles
                                                                "
                                                                :item="
                                                                    findScope(
                                                                        role
                                                                            ?.scope
                                                                            ?.id,
                                                                    )
                                                                "
                                                                v-else
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </transition>
                                    </div>
                                </div>
                            </div>
                        </transition>
                    </div>
                </div>
            </div>
        </template>

        <template #footer>
            <div
                class="flex items-center justify-between px-5 py-3 border-t border-gray-200 dark:border-gray-700"
            >
                <button
                    @click="toggleAll"
                    class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition"
                >
                    <i
                        :class="
                            allExpanded
                                ? 'mdi mdi-arrow-collapse-all'
                                : 'mdi mdi-arrow-expand-all'
                        "
                        class="mr-1"
                    ></i>
                    {{ allExpanded ? __("Collapse All") : __("Expand All") }}
                </button>
                <button
                    @click="dialog = false"
                    class="px-4 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition"
                >
                    {{ __("Done") }}
                </button>
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

const props = defineProps({
    item: { type: Object, required: true },
});

const page = usePage();

const dialog = ref(false);
const loading = ref(false);
const showSkeleton = ref(true);
const scopes = ref([]);
const user_scopes = ref([]);
const expandedGroups = ref({});
const expandedServices = ref({});

const totalGroups = computed(() => groupedScopes.value.length);

const totalRoles = computed(() => {
    return groupedScopes.value.reduce(
        (acc, group) =>
            acc +
            group.services.reduce(
                (sAcc, service) => sAcc + service.roles.length,
                0,
            ),
        0,
    );
});

const assignedScopesCount = computed(() => user_scopes.value.length);

const allExpanded = computed(() => {
    const groupsExpanded = Object.values(expandedGroups.value).every((v) => v);
    const servicesExpanded = Object.values(expandedServices.value).every(
        (v) => v,
    );
    return groupsExpanded && servicesExpanded && groupedScopes.value.length > 0;
});

const openDialog = async () => {
    dialog.value = true;
    loading.value = true;
    expandedGroups.value = {};
    expandedServices.value = {};

    await userRoles();
    await getScopes();

    if (groupedScopes.value.length > 0) {
        expandedGroups.value[0] = true;
    }

    loading.value = false;
};

const toggleGroup = (groupIndex) => {
    expandedGroups.value[groupIndex] = !expandedGroups.value[groupIndex];
};

const toggleService = (groupIndex, serviceIndex) => {
    const key = `${groupIndex}-${serviceIndex}`;
    expandedServices.value[key] = !expandedServices.value[key];
};

const toggleAll = () => {
    const newState = !allExpanded.value;
    groupedScopes.value.forEach((group, index) => {
        expandedGroups.value[index] = newState;
        group.services.forEach((_, serviceIndex) => {
            expandedServices.value[`${index}-${serviceIndex}`] = newState;
        });
    });
};

const userRoles = async () => {
    try {
        const res = await $server.get(props.item.links.scopes);
        if (res.status === 200) user_scopes.value = res.data.data;
    } catch (e) {
        if (e?.response?.data?.message) $notify.error(e.response.data.message);
    }
};

const getScopes = async () => {
    try {
        const res = await $server.get(page.props.api.scopes, {
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
    if (!user_scopes.value.length) return null;
    return user_scopes.value.find((item) => item.scope.id == scopeId);
};

const getAssignedCountForGroup = (group) => {
    let count = 0;
    group.services.forEach((service) => {
        service.roles.forEach((role) => {
            if (findScope(role?.scope?.id)) count++;
        });
    });
    return count;
};

const getAssignedCountForService = (service) => {
    return service.roles.filter((role) => findScope(role?.scope?.id)).length;
};

const getTotalRolesCountForGroup = (group) => {
    return group.services.reduce(
        (acc, service) => acc + service.roles.length,
        0,
    );
};

const getGroupProgress = (group) => {
    const assigned = getAssignedCountForGroup(group);
    const total = getTotalRolesCountForGroup(group);
    return total > 0 ? (assigned / total) * 100 : 0;
};

const groupedScopes = computed(() => {
    const grouped = {};

    scopes.value.forEach((item) => {
        const { service, role } = item;
        const group = service.group;
        const groupName = group.name;
        const serviceName = service.name;

        const scopeData = {
            id: item.id,
            gsr_id: item.gsr_id,
            public: item.public,
            active: item.active,
            web: item.web,
            api_key: item.api_key,
            links: { scopes: props.item.links.scopes },
        };

        const roleData = {
            id: role.id,
            name: role.name,
            slug: role.slug,
            description: role.description,
            scope: scopeData,
        };

        if (!grouped[groupName]) {
            grouped[groupName] = {
                name: groupName,
                description: group.description,
                services: {},
            };
        }

        if (!grouped[groupName].services[serviceName]) {
            grouped[groupName].services[serviceName] = {
                name: service.name,
                description: service.description,
                roles: [],
            };
        }

        grouped[groupName].services[serviceName].roles.push(roleData);
    });

    return Object.values(grouped).map((group) => ({
        ...group,
        services: Object.values(group.services),
    }));
});
</script>

<style scoped>
.slide-enter-active,
.slide-leave-active {
    transition: all 0.15s ease;
}

.slide-enter-from,
.slide-leave-to {
    opacity: 0;
    transform: translateY(-3px);
}
</style>
